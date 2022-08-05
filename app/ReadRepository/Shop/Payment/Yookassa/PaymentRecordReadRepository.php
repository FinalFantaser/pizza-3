<?php

namespace App\ReadRepository\Shop\Payment\Yookassa;

use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use Faker\Provider\ar_EG\Payment;

class PaymentRecordReadRepository{
    public function getMethods()
    {
        return PaymentRecord::orderBy('id');
    } //getMethods

    public function findByOrder(int $order_id): PaymentRecord
    {
        return PaymentRecord::where('order_id', $order_id)->paginate();
    } //findByOrder
};