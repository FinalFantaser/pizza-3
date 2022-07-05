<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Product;

class OrderItemRepository
{
    public function create(Order $order, Product $product, int $quantity
    ): OrderItem
    {
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'product_quantity' => $quantity,
            'total_price' => $product->price * $quantity,
        ]);

        return $orderItem;
    }
}
