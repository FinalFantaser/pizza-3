<?php

namespace App\Models\Shop\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $table = 'payment_records';

    protected $fillable = [
        'order_id',
        'method_id',
        'title',
        // 'status',
        'sum',
        'change_sum',
        'payer',
    ];

    protected $casts = [
        // 'status' => 'boolean',
        'sum' => 'integer',
        'change_sum' => 'integer',
    ];

    //Возможные плательщики
    const PAYER_CUSTOMER = 'customer'; //Клиент
    const PAYER_ADMIN = 'admin'; //Админ
}
