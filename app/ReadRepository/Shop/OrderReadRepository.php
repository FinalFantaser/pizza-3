<?php

namespace App\ReadRepository\Shop;

use App\Models\Shop\Order\Order;
use App\Models\Shop\City;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function findByCity(City $city, string|array $with = null){
        $orders = Order::join('order_customer_data', 'orders.id', '=', 'order_customer_data.id')
                    ->join('cities', 'order_customer_data.city_id', '=', 'cities.id')
                    ->where('order_customer_data.city_id', $city->id)
                    ->when(function($query) use ($with){
                        return is_null($with) ? $query : $query->with($with);
                    })
                    ->get();

        //Если модель не найдена
        if($orders->isEmpty())
            throw new ModelNotFoundException;

        return $orders;
    } //findByCity

    public function findByToken(string $token, string|array $with = null){
        $order = Order::where('token', $token)
                    ->when(function($query) use ($with){
                        return is_null($with) ? $query : $query->with($with);
                    })
                    ->first();

        //Если модели не найдены
        if(!$order->exists())
            throw new ModelNotFoundException;

        return $order;
    } //findByToken
}
