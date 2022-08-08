<?php

namespace App\Events\Order;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\PaymentMethod;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public PaymentMethod $method,
        public int $changeSum,
        public string $payer
    ){}

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
