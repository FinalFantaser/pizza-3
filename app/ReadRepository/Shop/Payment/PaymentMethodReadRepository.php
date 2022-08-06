<?php

namespace App\ReadRepository\Shop\Payment;

use App\Models\Shop\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentMethodReadRepository{
    public function getMethods()
    {
        return PaymentMethod::orderBy('id');
    } //getMethods

    public function findById(int $id): PaymentMethod
    {
        return PaymentMethod::findOrFail($id);
    } //findById

    public function findByCode(int $code): PaymentMethod
    {
        $paymentMethod = PaymentMethod::where('code', $code)->first();
        if(is_null($paymentMethod))
            throw new ModelNotFoundException;
        
        return $paymentMethod;
    } //findByName
};