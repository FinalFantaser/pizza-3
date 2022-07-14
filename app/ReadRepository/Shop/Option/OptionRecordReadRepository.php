<?php

namespace App\ReadRepository\Shop\Option;

use App\Models\Shop\Product;
use App\Models\Shop\Option\OptionRecord;

class OptionRecordReadRepository{
    public function getMethods()
    {
        return OptionRecord::orderBy('id');
    } //getMethods

    public function findById(int $id): OptionRecord
    {
        return OptionRecord::findOrFail($id);
    } //findById

    public function findByProduct(Product $product)
    {
        return OptionRecord
            ::join('product_option_record', 'option_records.id', '=', 'product_option_record.option_record_id')
            ->where('product_option_record.product_id', $product->id)
            ->get();
    } //findByProduct
};