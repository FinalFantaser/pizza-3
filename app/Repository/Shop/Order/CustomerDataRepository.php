<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Order\CustomerData;

use App\Models\Shop\City;

class CustomerDataRepository
{
    public function create(
        string $name,
        string $email,
        int $phone,
        int $city_id,
        string $address
    ): CustomerData
    {
        $customerData = CustomerData::create([
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
}
