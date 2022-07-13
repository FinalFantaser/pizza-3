<?php

namespace App\Models\Shop\Option;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];
}
