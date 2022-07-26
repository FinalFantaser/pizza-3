<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Order\CustomerData;

use App\Models\Shop\City;
use App\Models\Shop\Order\Order;

class CustomerDataRepository
{
    public function create(
        Order $order,
        string $name,
        string|null $email,
        int $phone,
        int $city_id,
        string $address
    ): CustomerData
    {
        $customerData = CustomerData::create([
            'order_id' => $order->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'city_id' => $city_id,
            'address' => $address
        ]);

        return $customerData;
    } //create

    public function remove(CustomerData $customerData): void
    {
        $customerData->delete();
    } //remove

    public function removeByOrder(Order $order): void //Удалить данные по id заказа
    {
        CustomerData::where('order_id', $order->id)->delete();
    } //removeByOrder
}
