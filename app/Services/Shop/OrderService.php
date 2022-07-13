<?php
namespace App\Services\Shop;

use App\Models\Shop\Order\Order;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Models\Shop\City;
use App\Models\Shop\Order\OrderItem;
use App\Repository\Shop\Order\OrderRepository;
use App\ReadRepository\Shop\OrderReadRepository;
use App\Repository\Shop\Order\CustomerDataRepository;
use App\Repository\Shop\Order\OrderItemRepository;
use Illuminate\Support\Facades\DB;

class OrderService{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderReadRepository $readRepository,
        private CustomerDataRepository $customerDataRepository,
        private OrderItemRepository $orderItemRepository,
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
        DB::transaction(function() use ($request){
            
            //Создание записи о заказе
            $order = $this->orderRepository->create(
                note: $request->note,
                totalPrice: 0 //Пока что сумма нулевая, она будет посчитана после создания orderItems
            );

            //Создание данных о клиенте
            $customerData = $this->customerDataRepository->create(
                name: $request->input('customer_data.name'),
                email: $request->input('customer_data.email'),
                phone: $request->input('customer_data.phone'),
                city_id: $request->input('customer_data.city_id'),
                address: $request->input('customer_data.address')
            );

            //Создание пунктов заказа
            $rawData = json_decode($request->order_items); 
            $items = array_map(function($value){
                $product = Product::findOrFail($value->product_id);

                return [
                    'product_id' => $value->product_id,
                    'product_quantity' => $value->product_quantity,
                ];
            }, $rawData);
            

            

            //Связывание данных между собой
            //...

            //Расчет суммы заказа

            $order->save();
        });
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