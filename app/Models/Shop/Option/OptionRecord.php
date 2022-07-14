<?php

namespace App\Models\Shop\Option;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'option_id',
        'product_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function option(){
        return $this->belongsTo(Option::class, 'option_id');
    } //option
}
