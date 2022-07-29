<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Order\CustomerData;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Order\OrderItem;
use Illuminate\Support\Str;

class OrderRepository
{
    public function create(?string $note, int $totalPrice, string $time = null): Order
    {
        $order = Order::create([
            'note' => $note,
            'cost' => $totalPrice,
            'token' => Order::generateToken(),
            'current_status' => Order::STATUS_NEW,
            'paid' => 0,
            'time' => $time,
        ]);

        return $order;
    } //create

    public function remove(Order $order): void
    {
        $order->delete();
    } //remove

    public function setDeliveryMethodInfo(Order $order, DeliveryMethod $method): void
    {
        $order->setDeliveryMethodInfo($method->id, $method->name, $method->cost);
    } //setDeliveryMethodInfo

    public function setCustomerDataInfo(Order $order, CustomerData $data): void
    {
        $order->setCustomerDataInfo($data->id);
    } //setCustomerDataInfo

    public function payByAdmin(Order $order): void
    {
        $order->pay('Paid by Admin');
    } //payByAdmin

    public function payByCustomer(Order $order, $method): void
    {
        $order->pay($method);
    } //payByCustomer

    public function makeSent(Order $order): void
    {
        $order->send();
    } //makeSent

    public function makeCompleted(Order $order): void
    {
        $order->complete();
    } //makeCompleted

    public function cancelByAdmin(Order $order, $reason): void
    {
        $order->cancelByAdmin($reason);
    } //cancelByAdmin

    public function cancelByManager(Order $order, $reason): void
    {
        $order->cancelByManager($reason);
    } //cancelByAdmin

    public function cancelByCustomer(Order $order, $reason): void
    {
        $order->cancelByCustomer($reason);
    } //cancelByUser
}
