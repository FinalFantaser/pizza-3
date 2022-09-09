<?php

namespace App\ReadRepository\Shop\Order;

use App\Models\Shop\Order\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderItemReadRepository{
    public function __construct(){} //Конструктор

    public function getMethods()
    {
        return OrderItem::orderByDesc('created_at');
    } //getMethods

    public function popular() //Найти наиболее часто заказываемые продукты
    {
        return 
            OrderItem::groupBy('product_id')
                ->select('product_id', DB::raw('count(*) as total'))
                ->orderByDesc('total')
                ->with(['product', 'product.optionRecords'])
                ->paginate(50);
    } //popular
};