<?php

namespace App\ReadRepository\Shop\Delivery;

use App\Models\Shop\Delivery\PickupPoint;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PickupPointReadRepository{
    public function getMethods()
    {
        return PickupPoint::orderBy('id')->with('city:id,name');
    } //getMethods

    public function findById(int $id): PickupPoint
    {
        $city = PickupPoint::where('id', $id)->with('city:id,name')->first();
        if(is_null($city))
            throw new ModelNotFoundException;
        return $city;
    } //findById

    public function findByCity(int $city_id)
    {
        $cities = PickupPoint::where('city_id', $city_id)->with('city:id,name')->get();
        if($cities->isEmpty())
            throw new ModelNotFoundException;
        
        return $cities;
    } //findByCity
};