<?php

namespace App\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop\City;
use App\Models\Shop\DeliveryMethod;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Order\CustomerData;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_data_id',
        'delivery_method_id', 'delivery_method_name',
        'delivery_method_cost', 'cost', 'note',
        'current_status', 'cancel_reason'
    ];

    public function setDeliveryMethodInfo(int $id, string $name, int $cost): void
    {
        $this->update([
            'delivery_method_id' => $id,
            'delivery_method_name' => $name,
            'delivery_method_cost' => $cost,
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
    
    public function pay($method)
    {
        if ($this->isPaid()) {
            throw new DomainException('Order is already paid.');
        }

        $this->payment_method = $method;
        $this->addStatus('paid');
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new DomainException('Order is already sent.');
        }

        $this->addStatus('sent');
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new DomainException('Order is already completed.');
        }

        $this->addStatus('completed');
    }

    public function cancelByAdmin($reason): void
    {
        $this->cancel($reason);
        $this->addStatus('cancelled');
    }

    public function cancelByUser($reason): void
    {
        if (!$this->canBeCanceled()) {
            throw new DomainException('Order cannot be canceled.');
        }

        $this->cancel($reason);
        $this->addStatus('cancelled by customer');
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
        return $this->current_status === 'new';
    }

    public function isPaid(): bool
    {
        return $this->current_status === 'paid';
    }

    public function isSent(): bool
    {
        return $this->current_status === 'sent';
    }

    public function isCompleted(): bool
    {
        return $this->current_status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->current_status === 'cancelled'
            || $this->current_status === 'cancelled by customer';
    }

    public function isCancelledByCustomer(): bool
    {
        return $this->current_status === 'cancelled by customer';
    }

    public function isCancelledByAdmin(): bool
    {
        return $this->current_status === 'cancelled';
    }

    private function addStatus($value): void
    {
        $this->update([
            'current_status' => $value
        ]);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id', 'id');
    }

    public function customerData()
    {
        return $this->belongsTo(CustomerData::class, 'customer_data_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
