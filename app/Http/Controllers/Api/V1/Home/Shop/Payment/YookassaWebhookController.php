<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\Yookassa\PaymentRecordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YookassaWebhookController extends Controller
{
    public function __construct(
        private PaymentRecord $record,
        private Order $order,
        private PaymentRecordService $paymentRecordService,
        private OrderService $orderService,
    )
    {} //Конструктор

    public function __invoke(Request $request)
    {
        $data = $request->all();
        Log::info(message: 'Получено уведомление по платежу №' . $data['object']['id'] . ': ' . $data['object']['status']);

        $record = $this->paymentRecordService->findByPaymentId($data['object']['id']);
        $order = $this->orderService->findById($record->order_id);

        $record->update([
            'paid' => $data['object']['paid'],
            'status' => $data['object']['status'],
        ]);
        
        $order->update([
            'paid' => ($record->isPaid() || $record->isSucceeded()) ? true : false
        ]);

        return response('Notification received', 200);
    }
}
