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
        int $phone,
        int $city_id,
        string $street,
        ?string $house,
        ?string $room,
        ?string $entrance,
        ?string $intercom,
        ?string $floor,
        ?string $corp,
    ): CustomerData
    {
        $customerData = CustomerData::create([
            'order_id' => $order->id,
            'name' => $name,
            'phone' => CustomerData::formatPhone($phone),
            'city_id' => $city_id,
            'street' => $street,
            'house' => $house,
            'room' => $room,
            'entrance' => $entrance,
            'intercom' => $intercom,
            'floor' => $floor,
            'corp' => $corp,
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
