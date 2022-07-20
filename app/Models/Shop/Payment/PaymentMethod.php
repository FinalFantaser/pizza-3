<?php

namespace App\Models\Shop\Payment;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'url', 'api_key', 'api_secret', 'additional'];

    protected $casts = [
        'additional' => AsArrayObject::class,
    ];

    public function pay(/*...*/){ //Универсальный метод, который вызывает метод, соответствующий способу платежа
        $method = $this->name . 'Payment';
        $this->$method(/*...*/);
    } //pay
}
