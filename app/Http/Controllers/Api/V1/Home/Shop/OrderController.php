<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Events\Order\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Http\Requests\Api\Home\Shop\Order\ShowRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop\Order\Order;
use App\Services\Shop\OrderService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
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

        OrderPlaced::dispatch($order);

        return new OrderResource($order);
    } //show
}
