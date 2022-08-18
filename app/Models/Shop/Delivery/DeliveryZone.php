<?php

namespace App\Models\Shop\Delivery;

use App\Models\Shop\City;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryZone extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'city_id',
        'restaurant_id',
        'code',
        'name',
        'sum_min',
        'time_min',
        'time_max',
        'delivery_price',
        'sum_for_free',
        'coordinates',
    ];

    protected $casts = [
        'restaurant_id' => 'string',
        'code' => 'string',
        'sum_min' => 'integer',
        'time_min' => 'integer',
        'time_max' => 'integer',
        'delivery_price' => 'integer',
        'sum_for_free' => 'integer',
        'coordinates' => AsArrayObject::class,
    ];

    protected $attributes = [
        'coordinates' => '[]',
    ];


    //
    // Eloquent-отношения
    //
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    } //city
}
