<?php

namespace App\ReadRepository\Shop\Option;

use App\Models\Shop\Option\OptionType;

class OptionTypeReadRepository{
    public function getMethods(){
        return OptionType::orderBy('id');
    } //getMethods

    public function findById(int $id): OptionType
    {
        return OptionType::findOrFail($id);
    } //findById
};