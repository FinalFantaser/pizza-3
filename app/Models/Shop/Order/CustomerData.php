<?php

namespace App\Models\Shop\Order;

use App\Models\Shop\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory;

    protected $table = 'order_customer_data';

    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'phone', 'city_id', 'address'
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }
}
