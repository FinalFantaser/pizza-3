<?php
namespace App\Services\Shop;

use App\Models\Shop\Order\Order;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Models\Shop\City;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Payment\PaymentMethod;
use App\Models\Shop\Payments\Record;
use App\Models\Shop\Product;
use App\ReadRepository\Shop\Delivery\DeliveryMethodReadRepository;
use App\ReadRepository\Shop\Delivery\PickupPointReadRepository;
use App\Repository\Shop\Order\OrderRepository;
use App\ReadRepository\Shop\Order\OrderReadRepository;
use App\ReadRepository\Shop\Payment\PaymentMethodReadRepository;
use App\ReadRepository\Shop\Payment\RecordReadRepository;
use App\ReadRepository\Shop\ProductReadRepository;
use App\Repository\Shop\Order\CustomerDataRepository;
use App\Repository\Shop\Order\OrderItemRepository;
use App\Repository\Shop\Payment\PaymentMethodRepository;
use App\Repository\Shop\Payment\RecordRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderReadRepository $orderReadRepository,
        private OrderItemRepository $orderItemRepository,
        private CustomerDataRepository $customerDataRepository,
        private DeliveryMethodReadRepository $deliveryMethodReadRepository,
        private PickupPointReadRepository $pickupPointReadRepository,
        private ProductReadRepository $productReadRepository,
        private PaymentMethodReadRepository $paymentMethodReadRepository,
        private RecordRepository $paymentRecordRepository,
        private RecordReadRepository $paymentRecordReadRepository,
    )
    {} //Конструктор

    //
    // Методы для администраторов
    //
    public function payByAdmin(Order $order, PaymentMethod $paymentMethod, int $changeSum = 0)
    {
        $this->orderRepository->pay(order: $order, method: $paymentMethod);
        $this->paymentRecordRepository->create(
            order: $order,
            method: $paymentMethod,
            payer: Record::PAYER_ADMIN,
            changeSum: $changeSum
        );
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
        $this->customerDataRepository->removeByOrder($order);
        $this->orderItemRepository->removeByOrder($order);
        $this->orderRepository->remove($order);
    } //remove


    //
    // Методы для клиентов
    //
    public function checkout(CheckoutRequest $request){  //Оформить заказ
        $order = DB::transaction(function() use ($request){            
            //Создание записи о заказе
            $order = $this->orderRepository->create(
                note: $request->note,
                totalPrice: 0, //Пока что сумма нулевая, она будет посчитана после создания orderItems
                paymentMethod: $this->paymentMethodReadRepository->findByCode($request->payment_method_id),
                time: $request->time
            );

            //Создание данных о клиенте
            $parsedJson = json_decode($request->customer_data);
            $customerData = $this->customerDataRepository->create(
                order: $order,
                name: $parsedJson->name,
                phone:  $parsedJson->phone,
                city_id: $parsedJson->city_id,
                street: $parsedJson->street,
                house: $parsedJson->house,
                room: $parsedJson->room,
                entrance: $parsedJson->entrance,
                intercom: $parsedJson->intercom,
                floor: $parsedJson->floor,
                corp: $parsedJson->corp,
            );

            //Создание пунктов заказа
            $rawData = json_decode($request->order_items);

            //Загрузка продуктов
            $products = $this->productReadRepository->findById(
                id: Arr::pluck($rawData, 'product_id'),
                with: 'optionRecords', 
            );

            $orderItemsData = array_map(function($value) use ($products, $order){
                $product = $products->where('id', $value->product_id)->first();
    
                $fullOptions = null; //Полная информация по опциям
                $additionalPrices = [];
                if(property_exists($value, 'options')){
                    $additionalPrices = Arr::flatten( //flatten убирает вложенные массивы, которые возвращает pluck
                        array_map(function($option) use ($product){ //Перебираются опции из запроса
                            $items = collect( $product->optionRecords->where('id', $option->id)->first()->items ); //Из опции продукта берутся items
                            return $items->whereIn('name', $option->selected)->pluck('price'); //Из items, где name совпадает с selected, берутся цены
                        }, $value->options)
                    );

                    $fullOptions = 
                        array_map(function($option) use ($product){ //Перебираются опции из запроса
                            $record = $product->optionRecords->where('id', $option->id)->first();
                            return [
                                'id' => $record->id,
                                'selected' => Arr::where($record->items, function($value, $key) use ($option){
                                    return in_array($value['name'], $option->selected);
                                })
                            ];
                        }, $value->options);
                }

                //Рассчёт общей стоимости пункта продукта
                $price = $value->product_quantity * ($product->price + (count($additionalPrices) ? array_sum($additionalPrices) : 0));
    
                return [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'product_quantity' => $value->product_quantity,
                    'product_options' => json_encode($fullOptions ?? []),
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
            if($request->has('pickup_point_id')){
                $pickupPoint = $this->pickupPointReadRepository->findById($request->pickup_point_id);
                $order->setPickupPointInfo(id: $pickupPoint->id, address: $pickupPoint->address);
            }

            //Расчет суммы заказа
            $order_items_total = array_sum(Arr::pluck($orderItemsData, 'total_price'));
            $order->cost = $order_items_total >= $deliveryMethod->free_from
                            ? $order_items_total
                            : $order_items_total + $deliveryMethod->cost;

            $order->save();

            return $order;
        });
        
        return $order;
    } //checkout

    public function payByCustomer(Order $order, PaymentMethod $paymentMethod, int $changeSum = 0)
    {
        $this->orderRepository->pay(order: $order, method: $paymentMethod);
        $this->paymentRecordRepository->create(
            order: $order,
            method: $paymentMethod,
            payer: Record::PAYER_CUSTOMER,
            changeSum: $changeSum
        );
    } //payByCustomer

    public function cancelByCustomer(CancelRequest $request, Order $order)
    {
       $this->orderRepository->cancelByCustomer($order, $request->reason);
    } //cancelByAdmin

    //
    //Методы для запросов В БД
    //
    public function getMethods(){
        return $this->orderReadRepository->getMethods();    
    } //getMethods

    public function findById(int $id): Order
    {
        return $this->orderReadRepository->findById($id);
    } //findById

    public function findByCity(City $city){
        return $this->orderReadRepository->findByCity($city);
    } //findByCity

    public function findByToken(string $token, string|array $with = null){
        return $this->orderReadRepository->findByToken(
            token: $token,
            with: $with);
    } //findByToken
}