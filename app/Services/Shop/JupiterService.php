<?php

namespace App\Services\Shop;

use App\Models\Shop\JupiterRecord;
use App\Models\Shop\Option\Option;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Product;
use App\ReadRepository\Shop\Option\OptionReadRepository;
use App\ReadRepository\Shop\Option\OptionRecordReadRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class JupiterService{
    public function __construct(
        private OptionReadRepository $optionReadRepository,
        private OptionRecordReadRepository $optionRecordReadRepository
    ){} //Конструктор

    public function findProduct(int $product_id, ?int $option_id = null, mixed $option_selected = null): ?object
    {
        $data =  DB::table('jupiter')
            ->where('product_id', $product_id)
            ->when(function($query) use ($option_id, $option_selected) {
                return is_null($option_id) && is_null($option_selected)
                    ? $query
                    : $query->where(['option_id' => $option_id, 'option_selected' => $option_selected]);

            })
            ->first(['name', 'price', 'jupiter_id']);

        return $data;
    } //findProduct

    public function findOption(int $option_id, mixed $option_selected): ?object
    {
        $data = DB::table('jupiter')
            ->whereNull('product_id')
            ->where(['option_id' => $option_id, 'option_selected' => $option_selected])
            ->first(['name', 'price', 'jupiter_id']);

        return $data;
    }

    public function makeJupiterRecords(Order $order)
    {
        //Подгрузка связей
        $order->load(['items.product.optionRecords.option']);

        $jupiterItems = [];

        // 1. Перебираются опции OrderItem
        // 2. Заготовлены пустые поля option_id, option_selected
        // 3. Если одна из опций - это size, то option_id и option_selected заполняются и прерываются
        // 4. Сначала разаскивается продукт по вводным
        // 5. После этого перебираются опции продукта, которые не size

        foreach($order->items as $item){
            $option_id = null;
            $option_selected = null;
            
            //Поиск опции size
            foreach($item->product_options as $option){
                $optionRecord = $this->optionRecordReadRepository->findById($option['id']);
                if($optionRecord->option->checkout_type === Option::TYPE_SIZE){
                    $option_id = $option['id'];
                    $option_selected = Arr::first(array: $option['selected'])['name'];
                    break;
                }
            }

            //Поиск по базе данных с полученными вводными
            $rawData = $this->findProduct(product_id: $item->product_id, option_id: $option_id, option_selected: $option_selected);
            $product = new JupiterRecord(id: $rawData->jupiter_id, parent_id: null, name: $rawData->name, price: $rawData->price, quantity: $item->product_quantity);
            
            //Перебирание остальных опций, которые не size
            foreach($item->product_options as $option){
                $optionRecord = $this->optionRecordReadRepository->findById($option['id']);
                if($optionRecord->option->checkout_type === Option::TYPE_SIZE)
                    continue;
                
                foreach($option['selected'] as $selected){
                    $raw = $this->findOption(option_id: $option['id'], option_selected: $selected['name']);
                    // throw new Exception(message: json_encode($raw));
                    if(!is_null($raw))
                        $product->addOption(new JupiterRecord(id: $raw->jupiter_id, parent_id: $product->id, name: $raw->name, price: $raw->price, quantity: $selected['quantity'] ?? 1));
                }
            }
            
            //Добавлена продукта в окончательный набор
            if(!is_null($product))
                $jupiterItems[] = $product;
        }

        // return $order->items;
        return $jupiterItems;
    } //makeJupiterRecords

    public function makeXml(Order $order): void //Генерация XML-файла для Юпитера
    {
        //Загрузка данных
        $order->load(['customerData', 'customerData.city', 'pickupPoint', 'items', 'payment', 'deliveryZone']);

        //Поиск записей из базы данных Юпитера
        $jupiterItems = $this->makeJupiterRecords($order);

        //Генерация XML-файла
        $document = View::make('jupiter.order', ['order' => $order, 'jupiterItems' => $jupiterItems]);

        if (env(key: 'JUPITER_TEST', default: true))
            Storage::disk('public')->put('JUPITER_TEST/ORDER_'.$order->id.'.xml', $document);
        else
            Storage::disk('jupiter_ftp')->put( env('TO_JUPITER_FOLDER') . '/ORDER_'.$order->id.'.xml', $document);
    } //makeXml

    public function addProduct(string $name, int $price, int $product_id, int $jupiter_id, ?int $option_id = null, mixed $option_selected = null): void //Добавить продукт в таблицу Jupiter
    {
        DB::table('jupiter')->insert([
            'name' => $name,
            'price' => $price,
            'product_id' => $product_id,
            'jupiter_id' => $jupiter_id,
            'option_id' => $option_id,
            'option_selected' => $option_selected,
        ]);
    } //addProduct

    public function addOption(string $name, int $price, int $jupiter_id, int $option_id, mixed $option_selected): void
    {
        DB::table('jupiter')->insert([
            'name' => $name,
            'price' => $price,
            'product_id' => null,
            'jupiter_id' => $jupiter_id,
            'option_id' => $option_id,
            'option_selected' => $option_selected,
        ]);
    } //addOption

    public function remove(int $id): void
    {
        DB::table('jupiter')->where('id', $id)->delete();
    } //remove

    public function makeDraft(?bool $truncate = false): void //Добавить черновые записи в таблицу для дальнейшей работы
    {
        //Очистка таблицы, если указано
        if($truncate)
            DB::table('jupiter')->truncate();

        Product::with(['optionRecords.option'])
            ->lazyById(chunkSize: 100)
            ->each(function($product){
                //Добавление продукта без опций
                $this->addProduct(name: $product->name, price: $product->price, product_id: $product->id, jupiter_id: 0);

                //Добавление опций
                foreach($product->optionRecords as $optionRecord){
                    //Если опция является размером, добавляется продукт вместе с размером
                    if($optionRecord->option->checkout_type === Option::TYPE_SIZE){
                        foreach($optionRecord->items as $item)
                            $this->addProduct(
                                name: $product->name . ' ' . $item['name'],
                                price: $item['price'],
                                product_id: $product->id,
                                jupiter_id: 0,
                                option_id: $optionRecord->option_id,
                                option_selected: $item['name']
                            );
                    }
                    //Если опция является чем-то другим, добавляется опция без продукта
                    else{
                        foreach($optionRecord->items as $item)
                            $this->addOption(
                                name: $item['name'],
                                price: $item['price'],
                                jupiter_id: 0,
                                option_id: $optionRecord->option_id,
                                option_selected: $item['name']
                            );
                    }
                }
            });
    } //makeDraft

    public function findAll(int $perPage = 50): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return DB::table('jupiter')->orderBy('id')->paginate($perPage);
    } //findAll
};