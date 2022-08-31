<?php

namespace App\Models\Shop\Order;

use App\Models\Shop\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory;

    protected $table = 'order_customer_data';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'name',
        'phone',
        'city_id',
        'actual_city',
        'street',
        'house',
        'room',
        'entrance',
        'intercom',
        'floor',
        'corp'
    ];

    public static  function formatPhone(string $phone): string //Переделать номер телефона под нужный формат
    {
        $res = preg_replace(
            pattern:
            [
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ],
            replacement: '8$2$3$4$5',
            subject: trim($phone)
        );

        return $res;
    } //formatPhone

    public function getAddressAttribute(): string
    {
        return implode(
            separator: ', ',
            array: [
                $this->street,
                $this->house,
                $this->room,
                $this->entrance,
                $this->intercom,
                $this->floor,
                $this->corp,
            ]
        );
    }


    //
    //  Eloquent-отношения
    //
    public function city(){
        return $this->belongsTo(City::class);
    }
}
