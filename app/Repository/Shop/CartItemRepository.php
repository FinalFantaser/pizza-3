<?php

namespace App\Repository\Shop;

use App\Models\Shop\CartItem;
use App\Models\Shop\Product;

class CartItemRepository
{
    public function create(
        Product $product,
        int $quantity
    ): CartItem {
        $cartItem = CartItem::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $product->price_sale ?? $product->price,
            'total_weight' => $product->properties['weight'],
        ]);

        return $cartItem;
    }

    public function update(CartItem $cartItem, $number)
    {
        if ($cartItem->quantity === 1 && $number < 0) {
            return;
        }

        $cartItem->update([
            'quantity' => $cartItem->quantity + $number
        ]);

        $this->recountTotalPrice($cartItem);
        $this->recountTotalWeight($cartItem);
    }

    public function remove(CartItem $cartItem): void
    {
        $cartItem->delete();
    }

    public function increaseProductsCount(CartItem $cartItem, int $quantity = 1): void
    {
        $cartItem->increaseProductsCount($quantity);
    }

    public function recountTotalPrice(CartItem $cartItem): void
    {
        $cartItem->recountTotalPrice();
    }

    public function recountTotalWeight(CartItem $cartItem): void
    {
        $cartItem->recountTotalWeight();
    }
}
