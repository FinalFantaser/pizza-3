<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop\Order\Order;
use App\Services\Shop\OrderService;

class OrderController extends Controller
{
    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    } //Конструктор

    //
    //                  REST-API
    //
    public function index(){
        return OrderResource::collection(
            $this->service->getMethods()->paginate(20)
        );
    } //index

    public function show(Order $order){
        return new OrderResource($order->load(['deliveryMethod', 'customerData', 'customerData.city', 'pickupPoint', 'items', 'payment']));
    } //show

    public function destroy(Order $order){
        $this->service->remove($order);
        return response()->json(['message' => 'Заказ удалён']);
    } //destroy

    //
    //                  Управление заказами
    //    
    public function cancel(CancelRequest $request, Order $order)
    {
        $this->service->cancelByAdmin($request, $order);
        return response()->json(['message' => 'Заказ отменён']);
    } //cancel

    public function pay(Order $order)
    {
        $this->service->payByAdmin($order);
        return response()->json(['message' => 'Заказ оплачен']);
    } //pay

    public function send(Order $order)
    {
        $this->service->makeSent($order);
        return response()->json(['message' => 'Заказ отправлен']);
    } //sent

    public function complete(Order $order)
    {
        $this->service->makeCompleted($order);
        return response()->json(['message' => 'Заказ выполнен']);
    } //complete
}
