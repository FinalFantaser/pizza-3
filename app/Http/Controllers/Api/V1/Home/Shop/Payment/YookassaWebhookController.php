<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YookassaWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info(message: 'Получено уведомление:' . json_encode($request->all()));

        $data = $request->all();

        $record = PaymentRecord::where('response_received', true)
            ->where('payment_id', $data['object']['id'])->first();

        $order = Order::findOrFail($record->order_id);

        if($data['object']['status'] === 'succeeded' || $data['object']['paid'] === true){
            $record->update([
                'paid' => true,
                'status' => PaymentRecord::STATUS_SUCCEEDED,
            ]);
            
            $order->update([
                'paid' => ($record->isPaid() || $record->isSucceeded()) ? true : false
            ]);
        }

        return response('Notification received', 200);
    }
}
