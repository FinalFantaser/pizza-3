<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Order\CustomerData;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Payment\PaymentMethod;
use Illuminate\Support\Str;

class OrderRepository
{
    public function create(?string $note, int $totalPrice, PaymentMethod $paymentMethod, string $time = null): Order
    {
        $order = Order::create([
            'payment_method_id' => $paymentMethod->id,
            'payment_method_name' => $paymentMethod->title,
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

    public function pay(Order $order): void
    {
        $order->pay();
    } //pay


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
