<?php

namespace App\ReadRepository\Shop\Payment\Yookassa;

use App\Models\Shop\Payment\Yookassa\YookassaShop;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class YookassaShopReadRepository{
    public function getMethods(string|array $with = null)
    {
        return YookassaShop::when(function($query) use ($with){
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
        return $this
            ->getMethods($with)
            ->whereHas('cities', function($query) use ($city_id){
                $query->where('city_id', $city_id);
            })
            ->first();
    } //findByCity
};