<?php

namespace App\Repository\Shop\Option;

use App\Models\Shop\Product;
use App\Models\Shop\Option\OptionRecord;
use Illuminate\Support\Facades\DB;

class OptionRecordRepository{
    public function create(int $option_id, int $product_id, string $items): OptionRecord //Создание одной записи
    {
        return OptionRecord::create([
            'option_id' => $option_id,
            'product_id' => $product_id,
            'items' => json_decode($items)
        ]);
    } //create

    public function createBulk(array $data): void //Создание сразу нескольких записей
    {
        OptionRecord::insert($data);
    } //createBulk

    public function remove(OptionRecord $optionRecord): void
    {
        $optionRecord->delete();
    } //remove

    public function removeByProduct(Product $product): void
    {
        OptionRecord::where('product_id', $product->id)->delete();
    } //remove
};