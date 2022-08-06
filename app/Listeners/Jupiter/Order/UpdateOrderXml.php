<?php

namespace App\Listeners\Jupiter\Order;

use App\Events\Order\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderXml
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Order\OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        //
    }
}
