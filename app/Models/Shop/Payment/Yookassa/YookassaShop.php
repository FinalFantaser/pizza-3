<?php

namespace App\Models\Shop\Payment\Yookassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YookassaShop extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'shop_id',
        'api_token',
    ];
}
