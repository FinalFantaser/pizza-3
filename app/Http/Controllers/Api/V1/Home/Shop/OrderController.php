<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Order\CheckoutRequest;
use App\Models\Shop\Order\Order;
use App\Services\Shop\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ){} //Конструктор

    public function checkout(CheckoutRequest $request){
        $this->orderService->checkout($request);
        return response()->json(['message' => 'Заказ оформлен', 'api_token' => '...'], 201);
    } //checkout
}
