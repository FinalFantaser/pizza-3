<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'cost', 'free_from', 'min_weight', 'max_weight',
    ];
}
