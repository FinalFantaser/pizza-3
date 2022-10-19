<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Events\Order\OrderPaid;
use App\Http\Controllers\Controller;
use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\PaymentMethod;
use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use App\Models\Shop\Payments\Record;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\PaymentMethodService;
use App\Services\Shop\Payment\Yookassa\PaymentRecordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YookassaWebhookController extends Controller
{
    public function __construct(
        private PaymentRecord $record,
        private Order $order,
        private PaymentMethodService $paymentMethodService,
        private PaymentRecordService $paymentRecordService,
        private OrderService $orderService,
    )
    {} //Конструктор

    public function __invoke(Request $request)
    {
        $data = $request->all();
        Log::info(message: 'Получено уведомление по платежу №' . $data['object']['id'] . ': ' . $data['object']['status']);

        //Загрузка данных
        $record = $this->paymentRecordService->findByPaymentId($data['object']['id']);
        $order = $this->orderService->findById($record->order_id);
        // $order->load(['deliveryMethod']);

        // $paymentCode = $order->delivery_method === DeliveryMethod::TYPE_COURIER ? PaymentMethod::CODE_ONLINE_DELIVERY : PaymentMethod::CODE_ONLINE_PICKUP;
        $paymentCode = PaymentMethod::CODE_ONLINE_PICKUP;

        //Данные попытки платежа обновляются
        $record->update([
            'paid' => $data['object']['paid'],
            'status' => $data['object']['status'],
        ]);
        
        //Если заказ оплачен, запустить событие
        if($record->isPaid() || $record->isSucceeded())
            OrderPaid::dispatch(
                $order,
                $this->paymentMethodService->findByCode($paymentCode),
                0,
                Record::PAYER_CUSTOMER
            );

        return response('Notification received', 200);
    }
}
