<?php

namespace App\Services\Shop;

use Illuminate\Support\Facades\DB;

class JupiterService{
    public function __construct(
    ){} //Конструктор

    public function findProduct(int $product_id, ?int $option_id = null, mixed $option_selected = null): ?object
    {
        $data =  DB::table('jupiter')
            ->where('product_id', $product_id)
            ->when(function($query) use ($option_id, $option_selected) {
                return is_null($option_id) && is_null($option_selected)
                    ? $query
                    : $query->where(['option_id' => $option_id, 'option_selected' => $option_selected]);

            })
            ->first(['name', 'price', 'jupiter_id']);

        return $data;
    } //findProduct

    public function findOption(int $option_id, mixed $option_selected): ?object
    {
        $data = DB::table('jupiter')
            ->whereNull('product_id')
            ->where(['option_id' => $option_id, 'option_selected' => $option_selected])
            ->first(['name', 'price', 'jupiter_id']);

        return $data;
    }
};