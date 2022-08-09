<?php

namespace App\Models\Shop\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'code'
    ];

    protected $casts = [
        'restaurant_id' => 'string',
        'zone_id' => 'string',
    ];

    //Коды типов оплаты
    public const CODE_CASH = 1; //Оплата наличными
    public const CODE_CARD = 2; //Оплата банковской картой
    public const CODE_ONLINE = 3; //Оплата онлайн

    public const CODE_CASH_PICKUP = 4;
    public const CODE_CASH_DELIVERY = 5;
    public const CODE_CARD_PICKUP = 6;
    public const CODE_CARD_DELIVERY = 7;
    public const CODE_ONLINE_PICKUP = 8;
    public const CODE_ONLINE_DELIVERY = 9;


    //Названия типов оплаты
    public const TITLE_CASH = 'Наличными'; //Оплата наличными
    public const TITLE_CARD = 'Банковской картой'; //Оплата банковской картой
    public const TITLE_ONLINE = 'Онлайн'; //Оплата онлайн

    public const TITLE_CASH_PICKUP = 'Наличные - Самовывоз';
    public const TITLE_CASH_DELIVERY = 'Наличные - Доставка';
    public const TITLE_CARD_PICKUP = 'Кредитная карта - Самовывоз';
    public const TITLE_CARD_DELIVERY = 'Кредитная карта - Доставка';
    public const TITLE_ONLINE_PICKUP = 'Оплата кредитной картой на сайте - Самовывоз';
    public const TITLE_ONLINE_DELIVERY = 'Оплата кредитной картой на сайте - Доставка';

    //
    //      Функции для создания типов оплаты. Используются, чтобы не создать тип оплаты с неправильным кодом
    //
    public static function create_CASH(string $title = self::TITLE_CASH): self //Создать способ оплаты наличными
    {
        return self::updateOrCreate(
            ['code' => self::CODE_CASH],
            ['title' => $title]
        );
    } //create_CASH

    public static function create_CARD(string $title = self::TITLE_CARD): self //Создать способ оплаты банковской картой
    {
        return self::updateOrCreate(
            ['code' => self::CODE_CARD],
            ['title' => $title]
        );
    } //create_CARD

    public static function create_ONLINE(string $title = self::TITLE_ONLINE): self //Создать способ оплаты наличными
    {
        return self::updateOrCreate(
            ['code' => self::CODE_ONLINE],
            ['title' => $title]
        );
    } //create_ONLINE
}
