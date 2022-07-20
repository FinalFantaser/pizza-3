<?php

namespace App\Repository\Shop\Payment;

use App\Models\Shop\Payment\PaymentMethod;

class PaymentMethodRepository{
    public function create(string $name, string $url = null, string $api_key = null, string $api_secret = null, string $additional = null): PaymentMethod
    {
        return PaymentMethod::create([
            'name' => $name,
            'url' => $url,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'additional' => $additional
        ]);
    } //create

    public function update(PaymentMethod $paymentMethod, string $name, string $url = null, string $api_key = null, string $api_secret = null, array $additional = null): PaymentMethod
    {
        $paymentMethod->update([
            'name' => $name,
            'url' => $url,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'additional' => is_null($additional) ? null : json_encode($additional)
        ]);

        return $paymentMethod;
    } //update

    public function remove(PaymentMethod $paymentMethod): void
    {
        $paymentMethod->delete();
    } //remove
};