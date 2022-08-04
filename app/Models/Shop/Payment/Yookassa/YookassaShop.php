<?php

namespace App\Models\Shop\Payment\Yookassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YookassaShop extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'shop_id',
        'api_token'
    ];

    public const PAYMENT_URL = 'https://api.yookassa.ru/v3/payments'; //URL, на который отправляется запрос на создание платежа

    public function cities(){
        return $this->belongsToMany(
            related: \App\Models\Shop\City::class,
            table: 'cities_yookassa_shops',
            foreignPivotKey: 'shop_id',
            relatedPivotKey: 'city_id'
        );
    } //cities

    public function city(){  //Обёртка для удобства получения города
        return $this->cities->first();
    } //city

    public function returnUrl(string $order_token){ //Генерирует URL, на который пользвателя возвращают после оплаты
        return route(
            name: 'api.home.payment.yookassa', //TODO В будущем заменить на более подходящий для редиректа URL (не API)
            parameters: ['token' => $order_token]
        );
    } //returnURL
}
