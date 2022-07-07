<?php

namespace App\Http\Controllers\Api\V1\Manager\Shop;

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

    public function index(){
        $orders = $this->service->
    }
}
