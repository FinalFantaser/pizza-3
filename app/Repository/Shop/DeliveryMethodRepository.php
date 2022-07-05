<?php

namespace App\Repository\Shop;

use App\Models\Shop\DeliveryMethod;

class DeliveryMethodRepository
{
    public function create(
        string $name, int $cost, $freeFrom,
        int $minWeight, int $maxWeight,
        int $sort
    ): DeliveryMethod
    {
        $method = DeliveryMethod::create([
            'name' => $name,
            'cost' => $cost,
            'free_from' => $freeFrom,
            'min_weight' => $minWeight,
            'max_weight' => $maxWeight
        ]);

        return $method;
    }

    public function update(
        DeliveryMethod $deliveryMethod,
        string $name, int $cost, $freeFrom,
        int $minWeight, int $maxWeight,
        int $sort
    ): void
    {
        $deliveryMethod->update([
            'name' => $name,
            'cost' => $cost,
            'free_from' => $freeFrom,
            'min_weight' => $minWeight,
            'max_weight' => $maxWeight,
            'sort' => $sort
        ]);
    }

    public function remove(DeliveryMethod $deliveryMethod): void
    {
        $deliveryMethod->delete();
    }
}
