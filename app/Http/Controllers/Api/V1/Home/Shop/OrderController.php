<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Http\Requests\Api\Home\Shop\Order\ShowRequest;
use App\Http\Resources\OrderResource;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\Yookassa\PaymentRecordService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentRecordService $paymentRecordService
    ){} //Конструктор

    public function checkout(CheckoutRequest $request){
        $token = $this->orderService->checkout($request);
        return response()->json(['message' => 'Заказ оформлен', 'api_token' => $token], 201);
    } //checkout

    public function show(ShowRequest $request){
        $order = $this->orderService->findByToken(
            token: $request->token,
            with: ['deliveryMethod', 'customerData', 'customerData.city', 'pickupPoint', 'items']
        );

        // if(!$order->isPaid())
        //     $this->paymentRecordService->check($order);

        
        return new OrderResource($order);
    } //show
}
