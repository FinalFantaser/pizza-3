<?php

namespace App\ReadRepository\Shop\Option;

use App\Models\Shop\Option\Option;

class OptionReadRepository{
    public function getMethods(){
        return Option::orderBy('id');
    } //getMethods

    public function findById(int $id): Option
    {
        return Option::findOrFail($id);
    } //findById
};