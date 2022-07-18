<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop\Product;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'order_id', 'product_id', 'product_name',
        'product_price', 'product_quantity', 'product_options', 'total_price'
    ];

    protected $casts = [
        'product_options' => 'array',
    ];

    public function getCost(): int
    {
        return $this->product_price * $this->product_quantity;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
