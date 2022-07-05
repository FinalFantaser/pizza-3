<?php

namespace App\ReadRepository\Shop;

use App\Models\Shop\Order\Order;
use App\Models\Shop\City;

class OrderReadRepository
{
    public function getMethods()
    {
        $orders = Order::orderByDesc('id');
        return $orders;
    }

    public function findById(int $id): ?Order
    {
        $order = Order::findOrFail($id);
        return $order;
    }
}
