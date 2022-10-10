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

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ){} //Конструктор

    public function checkout(CheckoutRequest $request){
        $order = $this->orderService->checkout($request);

        if(! in_array(needle: $request->payment_method_id, haystack: [PaymentMethod::CODE_ONLINE, PaymentMethod::CODE_ONLINE_DELIVERY, PaymentMethod::CODE_ONLINE_PICKUP]))
            OrderPlaced::dispatch($order);

        return response()->json(['message' => 'Заказ оформлен', 'api_token' => $order->token], 201);
    } //checkout

    public function show(ShowRequest $request){
        $order = $this->orderService->findByToken(
            token: $request->token,
            with: [
                /*'deliveryMethod',*/
                'customerData',
                'customerData.city',
                'pickupPoint',
                'items',
                'payment',
                'deliveryZone'
                ]
        );

        return new OrderResource($order);
    } //show
}