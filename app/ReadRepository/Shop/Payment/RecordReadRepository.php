<?php

namespace App\ReadRepository\Shop\Payment;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Payments\Record;

class RecordReadRepository{

    public function findById(int $id): Record
    {
        return Record::findOrFail($id);
    } //findById

    public function findByOrder(Order|int|string $order): ?Record //Поиска по модели, id или токену заказа
    {
        if($order instanceof Order) //Поиск по модели
            return Record::where('order_id', $order->id)->first();

        elseif(is_int($order)) //Поиск по id
            return Record::where('order_id', $order)->first();
            
        elseif(is_string($order)){ //Поиск по токену
            $repository = new \App\ReadRepository\Shop\Order\OrderReadRepository;
            $order = $repository->findByToken($order);

            return is_null($order)
                ? null
                : Record::where('order_id', $order->id)->first();
        }
    } //findByOrder

};