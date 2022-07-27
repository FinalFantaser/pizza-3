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

    public function popular(int $limit = 8) //Найти наиболее часто заказываемые продукты
    {
        return 
            OrderItem::groupBy('product_id')
                ->select('product_id', DB::raw('count(*) as total'))
                ->orderByDesc('total')
                ->limit($limit)
                ->with(['product', 'product.optionRecords'])
                ->get();
    } //popular
};