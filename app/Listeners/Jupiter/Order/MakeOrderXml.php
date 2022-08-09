<?php

namespace App\Listeners\Jupiter\Order;

use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class MakeOrderXml
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPlaced|OrderPaid $event)
    {
        // Проверка включения интеграции Юпитера
        if (!env('JUPITER_ENABLED')) return false;

        //Загрузка данных
        $event->order->load(['deliveryMethod', 'customerData', 'customerData.city', 'pickupPoint', 'items', 'payment']);

        //Генерация XML-файла
        $document = View::make('jupiter.order', ['order' => $event->order]);

        //Сохранение файла на FTP Юпитера
        Storage::disk('jupiter_ftp')->put( env('TO_JUPITER_FOLDER') . '/ORDER_'.$event->order->id.'.xml', $document);
    } //handle

    public function failed(OrderPlaced $event, $exception)
    {
        Log::error($exception->getMessage(), ['order_id' => $event->order->id]);
    } //failed
}
