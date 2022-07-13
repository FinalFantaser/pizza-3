<?php

namespace App\Models\Shop\Option;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Option\OptionType;

class Option extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type_id',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function type(){
        return $this->belongsTo(OptionType::class, 'type_id');
    } //option_type
}
