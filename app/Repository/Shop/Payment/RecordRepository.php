<?php

namespace App\Repository\Shop\Payment;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\PaymentMethod;
use App\Models\Shop\Payments\Record;

class RecordRepository{
    
    public function create(Order $order, PaymentMethod $method, string $payer, int $changeSum = 0): Record
    {
        return Record::create([
            'order_id' => $order->id,
            'method_id' => $method->id,
            'title' => $method->title,
            'sum' => $order->cost,
            'change_sum' => $changeSum,
            'payer' => $payer,
        ]);
    } //create

    public function remove(PaymentMethod $method): void
    {
        $method->delete();
    } //remove
};