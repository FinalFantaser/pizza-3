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

    public function findByCity(City $city){
        $orders = Order::join('order_customer_data', 'orders.id', '=', 'order_customer_data.id')
                    ->join('cities', 'order_customer_data.city_id', '=', 'cities.id')
                    ->get();
        return $orders;
    } //findByCity
}
