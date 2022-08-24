<?php

namespace App\Models\Shop\Option;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Option\OptionType;

class Option extends Model
{
    use HasFactory;

    public $timestamps = false;

    //Типы опции для корректного отображения в форме заказа
    const TYPE_SIZE = 'size'; //Размер
    const TYPE_ADDITIONAL = 'additional'; //Дополнительный ингредиент
    const TYPE_OTHER = 'other'; //Другое

    protected $fillable = [
        'name',
        'checkout_type',
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
