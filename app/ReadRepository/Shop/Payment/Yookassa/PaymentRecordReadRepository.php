<?php

namespace App\ReadRepository\Shop\Payment\Yookassa;

use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentRecordReadRepository{
    public function getMethods()
    {
        return PaymentRecord::orderBy('id');
    } //getMethods

    public function findByOrder(int $order_id): PaymentRecord
    {
        return PaymentRecord::where('order_id', $order_id)->paginate();
    } //findByOrder

    public function findCompleted(int $order_id): Collection //Поиск успешных платежей по заказу
    {
        return PaymentRecord::where('order_id', $order_id)->where('response_received', true)->where('paid', true)->get();
    } //findCompleted

    public function orderisPaid(int $order_id): bool //Проверяет, есть ли завершенные платежи по заказу
    {
        return $this->findCompleted($order_id)->count() > 0;
    } //orderisPaid

    public function findLatestPending(int $order_id): PaymentRecord //Найти последний незавершённый платёж
    {
        return PaymentRecord::where('order_id', $order_id)
            ->where('response_received', true)
            ->whereDate('expires_at', '>', now())
            ->where('status', PaymentRecord::STATUS_PENDING)
            ->latest()
            ->first();
    } //findLatestPending

    public function findLatestRecord(int $order_id, string|array $with = null): PaymentRecord //Найти последнюю запись о платеже по заказу
    {
        $record = PaymentRecord::where('order_id', $order_id)
            ->where('response_received', true)
            ->latest()
            ->when(function($query) use ($with){
                return is_null($with) ? $query : $query->with($with);
            })
            ->first();

        if(is_null($record))
            throw new ModelNotFoundException();
        
        return $record;
    } //findLatestRecord
};