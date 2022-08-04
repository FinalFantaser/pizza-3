<?php

namespace App\Models\Shop\Payment\Yookassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YookassaShop extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'shop_id',
        'api_token'
    ];

    public function cities(){
        return $this->belongsToMany(
            related: \App\Models\Shop\City::class,
            table: 'cities_yookassa_shops',
            foreignPivotKey: 'shop_id',
            relatedPivotKey: 'city_id'
        );
    } //cities
}
