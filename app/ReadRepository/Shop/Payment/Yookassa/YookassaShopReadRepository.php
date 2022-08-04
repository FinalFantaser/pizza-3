<?php

namespace App\ReadRepository\Shop\Payment\Yookasssa;

use App\Models\Shop\Payment\Yookassa\YookassaShop;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class YookassaShopReadRepository{
    public function getMethods(string|array $with = null)
    {
        return YookassaShop::orderBy('id')->when(function($query) use ($with){
            return is_null($with) ? $query : $query->with($with);
        });
    } //getMethods

    public function findById(int $id, string|array $with = null): YookassaShop
    {
        $shop = $this->getMethods($with)->where('id', $id)->first();

        if(is_null($shop))
            throw new ModelNotFoundException;

        return $shop;
    } //findById

    public function findByCity(int $city_id, string|array $with = null): YookassaShop
    {
        return $this->getMethods($with)->where('city_id', $city_id)->first();
    } //findByCity
};