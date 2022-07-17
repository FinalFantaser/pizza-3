<?php
namespace App\Services\Shop;

use App\Models\Shop\Order\Order;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Models\Shop\City;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Product;
use App\ReadRepository\Shop\DeliveryMethodReadRepository;
use App\Repository\Shop\Order\OrderRepository;
use App\ReadRepository\Shop\OrderReadRepository;
use App\ReadRepository\Shop\ProductReadRepository;
use App\Repository\Shop\Order\CustomerDataRepository;
use App\Repository\Shop\Order\OrderItemRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderReadRepository $orderReadRepository,
        private OrderReadRepository $readRepository,
        private OrderItemRepository $orderItemRepository,
        private CustomerDataRepository $customerDataRepository,
        private DeliveryMethodReadRepository $deliveryMethodReadRepository,
        private ProductReadRepository $productReadRepository,
    )
    {} //Конструктор

    //
    // Методы для администраторов
    //
    public function payByAdmin(Order $order)
    {
        $this->orderRepository->payByAdmin($order);
    } //payByAdmin

    public function makeSent(Order $order)
    {
        $this->orderRepository->makeSent($order);
    } //makeSent

    public function makeCompleted(Order $order)
    {
        $this->orderRepository->makeCompleted($order);
    } //makeCompleted

    public function cancelByAdmin(CancelRequest $request, Order $order)
    {
       $this->orderRepository->cancelByAdmin($order, $request->reason);
    } //cancelByAdmin

    public function remove(Order $order)
    {
        $this->orderRepository->remove($order);
    } //remove


    //
    // Методы для клиентов
    //
    public function checkout(CheckoutRequest $request){  //Оформить заказ
        $token = DB::transaction(function() use ($request){            
            //Создание записи о заказе
            $order = $this->orderRepository->create(
                note: $request->note,
                totalPrice: 0 //Пока что сумма нулевая, она будет посчитана после создания orderItems
            );

            //Создание данных о клиенте
            $parsedJson = json_decode($request->customer_data);
            $customerData = $this->customerDataRepository->create(
                name: $parsedJson->name,
                email: $parsedJson->email,
                phone:  $parsedJson->phone,
                city_id: $parsedJson->city_id,
                address: $parsedJson->address
            );

            //Создание пунктов заказа
            $rawData = json_decode($request->order_items);

            //Загрузка продуктов
            $products = $this->productReadRepository->findById(
                id: Arr::pluck($rawData, 'product_id'),
                with: 'optionRecords'
            );

            $orderItemsData = array_map(function($value) use ($products, $order){
                $product = $products->where('id', $value->product_id)->first();
    
                $additionalPrices = Arr::flatten( //flatten убирает вложенные массивы, которые возвращает pluck
                    array_map(function($option) use ($product){ //Перебираются опции из запроса
                        $items = collect( $product->optionRecords->where('id', $option->id)->first()->items ); //Из опции продукта берутся items
                        return $items->whereIn('name', $option->selected)->pluck('price'); //Из items, где name совпадает с selected, берутся цены
                    }, $value->options)
                );

                //Рассчёт общей стоимости пункта продукта
                $price = $value->product_quantity * ($product->price + (count($additionalPrices) ? array_sum($additionalPrices) : 0));
    
                return [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'product_quantity' => $value->product_quantity,
                    'total_price' => $price,
                    // 'additional' => $additionalPrices,
                ];
            }, $rawData);

            $this->orderItemRepository->createBulk($orderItemsData);

            //Загрузка способа доставки
            $deliveryMethod = $this->deliveryMethodReadRepository->findById($request->delivery_method_id);

            //Связывание данных между собой
            $order->setDeliveryMethodInfo($deliveryMethod->id, $deliveryMethod->name, $deliveryMethod->cost);
            $order->setCustomerDataInfo($customerData->id);

            //Расчет суммы заказа
            $order_items_total = array_sum(Arr::pluck($orderItemsData, 'total_price'));
            $order->cost = $order_items_total >= $deliveryMethod->free_from
                            ? $order_items_total
                            : $order_items_total + $deliveryMethod->cost;

            $order->save();

            return $order->token;
        });
        
        return $token;
    } //checkout

    public function payByCustomer(Order $order, $paymentMethod)
    {
        $this->orderRepository->payByCustomer($order, $paymentMethod);
    } //payByCustomer

    public function cancelByCustomer(CancelRequest $request, Order $order)
    {
       $this->orderRepository->cancelByUser($order, $request->reason);
    } //cancelByAdmin

    //
    //Методы для запросов В БД
    //
    public function getMethods(){
        return $this->orderReadRepository->getMethods();    
    } //getMethods

    public function findByCity(City $city){
        return $this->orderReadRepository->findByCity($city);
    } //findByCity
}