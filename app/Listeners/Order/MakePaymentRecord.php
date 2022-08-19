<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaid;
use App\Models\Shop\Payments\Record;
use App\Services\Shop\OrderService;
use DomainException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakePaymentRecord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private OrderService $service
    )
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
        //Загрузка данных
        $event->order->load([/*'deliveryMethod',*/ 'customerData', 'customerData.city', 'pickupPoint', 'items', 'payment']);

        //Создание записи о платеже
        if($event->payer === Record::PAYER_ADMIN)
            $this->service->payByAdmin(
                order: $event->order,
                paymentMethod: $event->method,
                changeSum: $event->changeSum
            );
        elseif($event->payer === Record::PAYER_CUSTOMER)
            $this->service->payByCustomer(
                order: $event->order,
                paymentMethod: $event->method,
                changeSum: $event->changeSum
            );
        else
            throw new DomainException(message: 'Invalid payer specified: ' . $event->payer);
    }
}
