<?php

namespace App\Repository\Shop\Delivery;

use App\Models\Shop\Delivery\PickupPoint;

class PickupPointRepository{
    public function create(int $city_id, string $address): PickupPoint
    {
        return PickupPoint::create(['city_id' => $city_id, 'address' => $address]);
    } //create

    public function update(PickupPoint $pickupPoint, int $city_id, string $address): PickupPoint
    {
        $pickupPoint->update(['city_id' => $city_id, 'address' => $address]);
        return $pickupPoint;
    } //create    

    public function remove(PickupPoint $pickupPoint): void
    {
        $pickupPoint->delete();
    } //remove
};