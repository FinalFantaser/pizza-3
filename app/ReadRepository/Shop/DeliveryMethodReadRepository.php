<?php

namespace App\ReadRepository\Shop;

use App\Models\Shop\DeliveryMethod;

class DeliveryMethodReadRepository
{
    public function getMethods()
    {
        $methods = DeliveryMethod::orderByDesc('id');
        return $methods;
    }

    public function findFreeFirst()
    {
        $methods = DeliveryMethod::where('free_from', '<>', '', 'and')->first();
        return $methods;
    }

    public function findById(int $id): ?DeliveryMethod
    {
        $method = DeliveryMethod::findOrFail($id);
        return $method;
    }

    public function findByWeight(int $weight)
    {
        $methods = DeliveryMethod::orderBy('sort')
            ->where('max_weight', '>', $weight)
            ->get();

        return $methods;
    }
}
