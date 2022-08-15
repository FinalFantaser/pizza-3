<?php

namespace App\Repository\Shop\Delivery;

use App\Models\Shop\Delivery\DeliveryZone;

class DeliveryZoneRepository{
    public function create(
        int $city_id,
        string $restaurant_id,
        string $code,
        string $name,
        int $sum_min,
        int $time_min,
        int $time_max,
        int $delivery_price,
        int $sum_for_free,
        array $coordinates
    ): DeliveryZone
    {
        return DeliveryZone::create([
            'city_id' => $city_id,
            'restaurant_id' => $restaurant_id,
            'code' => $code,
            'name' => $name,
            'sum_min' => $sum_min,
            'time_min' => $time_min,
            'time_max' => $time_max,
            'delivery_price' => $delivery_price,
            'sum_for_free' => $sum_for_free,
            'coordinates' => $coordinates
        ]);
    } //create

    public function updateOrCreate(
        int $city_id,
        string $restaurant_id,
        string $code,
        string $name,
        int $sum_min,
        int $time_min,
        int $time_max,
        int $delivery_price,
        int $sum_for_free,
        array $coordinates
    ): DeliveryZone
    {
        return DeliveryZone::updateOrCreate([
            'city_id' => $city_id,
            'name' => $name,
        ],
        [
            'restaurant_id' => $restaurant_id,
            'code' => $code,
            'sum_min' => $sum_min,
            'time_min' => $time_min,
            'time_max' => $time_max,
            'delivery_price' => $delivery_price,
            'sum_for_free' => $sum_for_free,
            'coordinates' => $coordinates
        ]);
    } //createOrUpdate

    public function update(
        DeliveryZone $deliveryZone,
        int $city_id,
        string $restaurant_id,
        string $code,
        string $name,
        int $sum_min,
        int $time_min,
        int $time_max,
        int $delivery_price,
        int $sum_for_free,
        array $coordinates
    ): DeliveryZone
    {
        $deliveryZone->update([
            'city_id' => $city_id,
            'restaurant_id' => $restaurant_id,
            'code' => $code,
            'name' => $name,
            'sum_min' => $sum_min,
            'time_min' => $time_min,
            'time_max' => $time_max,
            'delivery_price' => $delivery_price,
            'sum_for_free' => $sum_for_free,
            'coordinates' => $coordinates
        ]);

        return $deliveryZone;
    } //update

    public function remove(DeliveryZone $deliveryZone): void
    {
        $deliveryZone->delete();
    } //remove
};