<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Events\Order\OrderComplete;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Http\Requests\Api\Home\Shop\Order\ShowRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\PaymentMethod;
use App\Models\Shop\Payments\Record;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\PaymentMethodService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentMethodService $paymentMethodService
    ){} //Конструктор

    public function checkout(CheckoutRequest $request){
        $order = $this->orderService->checkout($request);
        
        return response()->json(['message' => 'Заказ оформлен', 'api_token' => $order->token], 201);
    } //checkout

    public function show(ShowRequest $request){
        $order = $this->orderService->findByToken(
            token: $request->token,
            with: ['customerData', 'customerData.city', 'pickupPoint', 'items', 'payment', 'deliveryZone']
        );

        OrderPaid::dispatch(
            $order,
            $this->paymentMethodService->findByCode(PaymentMethod::CODE_CARD_PICKUP),
            0,
            Record::PAYER_CUSTOMER
        );

        OrderComplete::dispatch($order);

        return new OrderResource($order);
    } //show
}