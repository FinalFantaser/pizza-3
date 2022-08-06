<?php

namespace App\Repository\Shop\Payment\Yookassa;

use App\Models\Shop\Payment\Yookassa\PaymentRecord;

use Illuminate\Support\Str;
use \Illuminate\Http\Client\Response;

class PaymentRecordRepository{
    public function register(int $order_id, int $shop_id): PaymentRecord //Зарегистрировать попытку платежа в базе данных приложения
    {
        return PaymentRecord::create([
            'idempotence_key' => Str::random(64),
            'order_id' => $order_id,
            'shop_id' => $shop_id,
        ]);
    } //initiate

    public function fill(PaymentRecord $record, Response $response): PaymentRecord //Заполнить запись о попытке платежа из полученного ответа
    {
        $record->update([
            'response_received' => true,
            'payment_id' => $response['id'],
            'status' => $response['status'],
            'paid' => $response['paid'],
            'amount' => $response['amount']['value'],
            'cancellation_details' => $response['cancellation_details'] ?? null,
        ]);

        return $record;
    } //fill

    public function remove(PaymentRecord $record): void
    {
        $record->delete();
    } //remove
};