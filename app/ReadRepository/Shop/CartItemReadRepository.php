<?php

namespace App\ReadRepository\Shop;

use App\Models\Shop\Cart\CartItem;

class CartItemReadRepository
{
    public function findById(int $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        return $cartItem;
    }
}
