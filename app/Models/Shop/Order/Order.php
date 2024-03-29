<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop\City;
use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Delivery\DeliveryZone;
use App\Models\Shop\Delivery\PickupPoint;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Order\CustomerData;
use App\Models\Shop\Payment\PaymentMethod;
use App\Models\Shop\Payments\Record;
use DomainException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_data_id',
        'delivery_method',
        'delivery_zone_id',
        'payment_method_id',
        'payment_method_name',
        'time',
        'cost',
        'note',
        'current_status',
        'paid',
        'viewed',
        'cancel_reason',
        'token',
    ];

    protected $casts = [
        'viewed' => 'boolean',
    ];

    //Статусы заказа
    const STATUS_NEW = 'new'; //Новый
    const STATUS_SENT = 'sent'; //Отправлено
    const STATUS_COMPLETED = 'completed'; //Завершен
    const STATUS_CANCELLED_BY_CUSTOMER = 'cancelled by customer'; //Отменён клиентом
    const STATUS_CANCELLED_BY_MANAGER = 'cancelled by manager'; //Отменён менеджером
    const STATUS_CANCELLED_BY_ADMIN = 'cancelled by admin'; //Отменён администратором

    static public function generateToken(): string
    {
        return Str::random(60);
    } //generateToken

    public function setDeliveryMethodInfo(string $method): void
    {
        $this->update([
            'delivery_method' => $method,
        ]);
    }

    public function setDeliveryZoneInfo(DeliveryZone $deliveryZone): void
    {
        $this->update([
            'delivery_zone_id' => $deliveryZone->id,
        ]);
    }

    public function setPickupPointInfo(int $id, string $address): void
    {
        $this->update([
            'pickup_point_id' => $id,
            'pickup_point_address' => $address,
        ]);
    }

    public function setCustomerDataInfo(int $customerDataId): void
    {
        $this->update([
            'customer_data_id' => $customerDataId
        ]);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function pay(PaymentMethod $method)
    {
        if ($this->isPaid()) {
            throw new DomainException('Order is already paid.');
        }

        $this->update([
            'paid' => true,
            'payment_method_id' => $method->code,
            'payment_method_name' => $method->title
        ]);
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new DomainException('Order is already sent.');
        }

        $this->addStatus(self::STATUS_SENT);
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            // throw new DomainException('Order is already completed.');
        }

        $this->addStatus(self::STATUS_COMPLETED);
    }

    public function cancelByAdmin($reason): void
    {
        $this->cancel($reason);
        $this->addStatus(self::STATUS_CANCELLED_BY_ADMIN);
    }

    public function cancelByManager($reason): void
    {
        $this->cancel($reason);
        $this->addStatus(self::STATUS_CANCELLED_BY_MANAGER);
    }

    public function cancelByCustomer($reason): void
    {
        if (!$this->canBeCanceled()) {
            throw new DomainException('Order cannot be canceled.');
        }

        $this->cancel($reason);
        $this->addStatus(self::STATUS_CANCELLED_BY_CUSTOMER);
    }

    private function cancel($reason): void
    {
        if ($this->isCancelled()) {
            throw new DomainException('Order is already cancelled.');
        }

        $this->update([
            'cancel_reason' => $reason
        ]);
    }

    public function getTotalCost(): int
    {
        return $this->cost + $this->delivery_cost;
    }

    public function canBePaid(): bool
    {
        return $this->isNew();
    }

    public function canBeCanceled(): bool
    {
        return $this->isNew();
    }

    public function canBeSent(): bool
    {
        return $this->isPaid();
    }

    public function canBeCompleted(): bool
    {
        return $this->isSent();
    }

    public function isNew(): bool
    {
        return $this->current_status === self::STATUS_NEW;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function isSent(): bool
    {
        return $this->current_status === self::STATUS_SENT;
    }

    public function isCompleted(): bool
    {
        return $this->current_status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->current_status === self::STATUS_CANCELLED_BY_ADMIN
            || $this->current_status === self::STATUS_CANCELLED_BY_MANAGER
            || $this->current_status === self::STATUS_CANCELLED_BY_CUSTOMER;
    }

    public function isCancelledByCustomer(): bool
    {
        return $this->current_status === self::STATUS_CANCELLED_BY_CUSTOMER;
    }

    public function isCancelledByAdmin(): bool
    {
        return $this->current_status === self::STATUS_CANCELLED_BY_ADMIN;
    }

    public function isCancelledByManager(): bool
    {
        return $this->current_status === self::STATUS_CANCELLED_BY_MANAGER;
    }

    public function isCourier(): bool
    {
        return $this->delivery_method === DeliveryMethod::TYPE_COURIER;
    }

    public function isPickup(): bool
    {
        return $this->delivery_method === DeliveryMethod::TYPE_PICKUP;
    }

    public function isViewed(): bool
    {
        return $this->viewed;
    }

    private function addStatus($value): void
    {
        $this->update([
            'current_status' => $value
        ]);
    }

    public function statusIs($status): bool
    {
        return $this->current_status === $status;
    }


    //
    //  Загрузка отношений Eloquent-моделей
    //
    // public function deliveryMethod()
    // {
    //     return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id', 'id');
    // }

    public function customerData()
    {
        return $this->belongsTo(CustomerData::class, 'customer_data_id', 'id');
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Record::class, 'order_id');
    }
    
    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone_id');
    }
    
}
