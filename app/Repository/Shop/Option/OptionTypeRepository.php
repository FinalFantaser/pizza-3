<?php

namespace App\Repository\Shop\Option;

use App\Models\Shop\Option\OptionType;

class OptionTypeRepository{
    public function create(string $name): OptionType
    {
        return OptionType::create(['name' => $name]);
    } //create

    public function update(OptionType $optionType, string $name): OptionType
    {
        $optionType->update(['name' => $name]);
        return $optionType;
    } //update
    
    public function remove($optionType): void
    {
        $optionType->delete();
    } //remove
};