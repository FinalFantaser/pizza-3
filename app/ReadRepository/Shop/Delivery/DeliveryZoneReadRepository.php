<?php

namespace App\ReadRepository\Shop\Delivery;

use App\Models\Shop\City;
use App\Models\Shop\Delivery\DeliveryZone;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class DeliveryZoneReadRepository{
    public function getMethods()
    {
        return DeliveryZone::orderBy('id');
    } //getMethods

    public function findById(int $id): DeliveryZone
    {
        return DeliveryZone::findOrFail($id);
    } //findById

    public function findByCity(City|int $city, string|array $with = null): Collection
    {
        return DeliveryZone::where('city_id', $city instanceof City ? $city->id : $city)
            ->when(function($query) use ($with){
                return is_null($with)
                ? $query
                : $query->with($with);
            })
            ->get();
    } //findByCity

};