<?php

namespace App\Listeners\Jupiter\Order;

use App\Events\Order\OrderComplete;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPlaced;
use App\Services\Shop\JupiterService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class MakeOrderXml
{
    public function __construct(
        private JupiterService $service
    )
    {
        //
    }

    public function handle(OrderPlaced|OrderPaid|OrderComplete $event)
    {
        // Проверка включения интеграции Юпитера
        if (!env(key: 'JUPITER_ENABLED', default: true)) return false;

        //Загрузка данных
        $event->order->load(['customerData', 'customerData.city', 'pickupPoint', 'items', 'payment', 'deliveryZone']);

        //Генерация XML-файла
        $this->service->makeXml($event->order);
        // $document = View::make('jupiter.order', ['order' => $event->order]);

        // if (env(key: 'JUPITER_TEST', default: true))
            // Storage::disk('public')->put('JUPITER_TEST/ORDER_'.$event->order->id.'.xml', $document);
        // else
            // Storage::disk('jupiter_ftp')->put( env('TO_JUPITER_FOLDER') . '/ORDER_'.$event->order->id.'.xml', $document);
    } //handle

    public function failed(OrderPlaced $event, $exception)
    {
        Log::error($exception->getMessage(), ['order_id' => $event->order->id]);
    } //failed
}
