<?php

namespace App\Models\Shop\Delivery;

use App\Models\Shop\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupPoint extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['city_id', 'address'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    } //city
}
