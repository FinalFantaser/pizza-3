<?php

namespace App\Models\Shop\Payment\Yookassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $table = 'yookassa_payment_records';
    protected $fillable = [
        'order_id',
        'response_received',
        'idempotence_key',
        'payment_id',
        'status',
        'paid',
        'amount',
        'cancellation_details',
    ];

    protected $casts = [
        'response_received' => 'boolean',
        'paid' => 'boolean',
        'amount' => 'integer',
        'cancellation_details' => 'array',
    ];

    //Возможные статусы заказа
    const STATUS_PENDING  = 'pending';
    const STATUS_WAITING_FOR_CAPTURE = 'waiting_for_capture';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_CANCELLED = 'canceled';

}
