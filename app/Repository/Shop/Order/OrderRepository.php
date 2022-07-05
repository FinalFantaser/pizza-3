<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\DeliveryMethod;
use App\Models\Shop\Order\CustomerData;
use App\Models\Shop\Order\Order;

class OrderRepository
{
    public function create($note, $totalPrice): Order
    {
        $order = Order::create([
            'note' => $note,
            'cost' => $totalPrice,
        ]);

        return $order;
    }

    public function setDeliveryMethodInfo(Order $order, DeliveryMethod $method): void
    {
        $order->setDeliveryMethodInfo($method->id, $method->name, $method->cost);
    }

    public function setCustomerDataInfo(Order $order, CustomerData $data): void
    {
        $order->setCustomerDataInfo($data->id);
    }

    public function payByAdmin(Order $order): void
    {
        $order->pay('Paid by Admin');
    }

    public function payByCustomer(Order $order, $method): void
    {
        $order->pay($method);
    }

    public function makeSent(Order $order): void
    {
        $order->send();
    }

    public function makeCompleted(Order $order): void
    {
        $order->complete();
    }

    public function cancelByAdmin(Order $order, $reason): void
    {
        $order->cancelByAdmin($reason);
    }

    public function cancelByUser(Order $order, $reason): void
    {
        $order->cancelByUser($reason);
    }

    public function remove(Order $order): void
    {
        $order->delete();
    }
}
