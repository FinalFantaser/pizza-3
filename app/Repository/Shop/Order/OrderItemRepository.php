<?php

namespace App\Repository\Shop\Order;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Product;

class OrderItemRepository
{
    public function create(Order $order, Product $product, int $quantity, array $options = []
    ): OrderItem
    {
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'product_quantity' => $quantity,
            'product_options' => $options,
            'total_price' => $product->price * $quantity,
        ]);

        return $orderItem;
    } //create

    public function createBulk(array $data) //Создать сразу много записей
    {
        OrderItem::insert($data);
    }

    public function remove(OrderItem $orderItem): void
    {
        $orderItem->delete();
    } //remove

    public function removeByOrder(Order $order): void //Удалить данные по id заказа
    {
        OrderItem::where('order_id', $order->id)->delete();
    } //removeByOrder
}
