<?php

namespace App\Repository\Shop\Payment;

use App\Models\Shop\Payment\PaymentMethod;
use DomainException;

class PaymentMethodRepository{

    public function create(int $code, ?string $title = null): PaymentMethod
    {
        switch($code){
            case PaymentMethod::CODE_CASH:
                return PaymentMethod::create_CASH($title);
            case PaymentMethod::CODE_CARD:
                return PaymentMethod::create_CARD($title);
            case PaymentMethod::CODE_ONLINE:
                return PaymentMethod::create_ONLINE($title);
            default:
                throw new DomainException(message: 'Метода платежа с таким кодом не существует');
        }
    } //create

    public function remove(PaymentMethod $paymentMethod): void
    {
        $paymentMethod->delete();
    } //remove
};