<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Shop\Product;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'quantity',
        'total_price', 'total_weight'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function increaseProductsCount(int $quantity): void
    {
        $currentQty = $this->quantity;

        $this->update([
            'quantity' => $currentQty + $quantity
        ]);
    }

    public function recountTotalPrice()
    {
        $current_price = $this->product->price_sale ?? $this->product->price;

        $this->update([
            'total_price' => $this->quantity * $current_price
        ]);
    }

    public function recountTotalWeight()
    {
        $this->update([
            'total_weight' => $this->quantity * $this->product->properties['weight']
        ]);
    }

    public function getImage()
    {
        return $this->product->imageUrl('product');
    }
}
