<?php

namespace App\Models\Shop\Option;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    //Типы опций
    const TYPE_LIST = 1;
    const TYPE_RADIO = 2;
    const TYPE_CHECKBOX = 3;
}
