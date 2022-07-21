<?php

namespace App\Repository\Shop\Delivery;

use App\Models\Shop\Delivery\DeliveryMethod;

class DeliveryMethodRepository
{
    public function create(
        string $name, string $type, int $cost, $freeFrom,
        int $minWeight, int $maxWeight
    ): DeliveryMethod
    {
        $method = DeliveryMethod::create([
            'name' => $name,
            'type' => $type,
            'cost' => $cost,
            'free_from' => $freeFrom,
            'min_weight' => $minWeight,
            'max_weight' => $maxWeight
        ]);

        return $method;
    }

    public function update(
        DeliveryMethod $deliveryMethod,
        string $name, string $type, int $cost, $freeFrom,
        int $minWeight, int $maxWeight,
    ): void
    {
        $deliveryMethod->update([
            'name' => $name,
            'type' => $type,
            'cost' => $cost,
            'free_from' => $freeFrom,
            'min_weight' => $minWeight,
            'max_weight' => $maxWeight,
        ]);
    }

    public function remove(DeliveryMethod $deliveryMethod): void
    {
        $deliveryMethod->delete();
    }
}
