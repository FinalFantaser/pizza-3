<?php

namespace App\Models\Shop\Payment\Yookassa;

use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'expires_at',
    ];

    protected $casts = [
        'response_received' => 'boolean',
        'paid' => 'boolean',
        'amount' => 'integer',
        'cancellation_details' => 'array',
    ];

    protected $dates = [
        'expires_at'
    ];

    //URL, с которого запрашивается статус платежа
    public const CHECK_STATUS_URL = 'https://api.yookassa.ru/v3/payments';

    //Возможные статусы заказа
    public const STATUS_PENDING  = 'pending';
    public const STATUS_WAITING_FOR_CAPTURE = 'waiting_for_capture';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_CANCELLED = 'canceled';

    public function shop(): BelongsTo
    {
        return $this->belongsTo(
            related: YookassaShop::class,
            foreignKey: 'shop_id'
        );
    }

    //
    //  Методы для валидации платежа
    //
    public function hasResponse(): bool
    {
        return $this->response_received;
    } //hasResponse

    public function isPaid(): bool
    {
        return $this->paid;
    } //isPaid

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    } //isExpired

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    } //isCancelled

    public function isSucceeded(): bool
    {
        return $this->status === self::STATUS_SUCCEEDED;
    } //isSucceeded

    public function validate(): void //Проверяет, что запись о платеже является пригодной для оплаты
    {
        if($this->isExpired())
            throw new DomainException(message: 'У платежа истёк срок действия. Попробуйте ещё раз');
        if($this->isPaid())
            throw new DomainException(message: 'Заказ №'.$this->order_id.' уже оплачен');
        if($this->isCancelled())
            throw new DomainException(message: 'Текущий платёж был отменён. Попробуйте ещё раз');
    } //validate
}
