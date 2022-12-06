<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPlaced;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private EmailService $service
    )
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Order\OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $this->service->send($event->order);
    }
}
