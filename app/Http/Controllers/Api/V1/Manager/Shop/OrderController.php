<?php

namespace App\Http\Controllers\Api\V1\Manager\Shop;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop\Order\Order;
use App\Services\Shop\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    } //Конструктор

    public function index(){
        $orders = $this->service->findByCity(
            Auth::guard('api')->user()->city
        );

        return OrderResource::collection($orders);
    } //index

    public function show(Order $order){
        return new OrderResource(
            $order->load([/*'deliveryMethod',*/ 'customerData'])
        );
    } //show

    public function cancel(CancelRequest $request, Order $order){
        $this->service->cancelByAdmin($request, $order);
        return response()->json(['message' => 'Заказ помечен как отменённый']);
    } //cancel

    public function pay(Order $order){
        $this->service->payByAdmin($order);
        return response()->json(['message' => 'Заказ помечен как оплаченный']);
    } //pay

    public function send(Order $order){
        $this->service->makeSent($order);
        return response()->json(['message' => 'Заказ помечен как отправленный']);
    } //pay

    public function complete(Order $order){
        $this->service->makeCompleted($order);
        return response()->json(['message' => 'Заказ помечен как доставленный']);
    } //pay
}
