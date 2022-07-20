<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Payment\PaymentMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Payment\PaymentMethod\UpdateRequest;
use App\Http\Resources\Payment\PaymentMethodResource;
use App\Models\Shop\Payment\PaymentMethod;
use App\Services\Shop\Payment\PaymentMethodService;

class PaymentMethodController extends Controller
{
    public function __construct(
        private PaymentMethodService $paymentMethodService
    )
    {} //Конструктор

    public function index()
    {
        return PaymentMethodResource::collection(
            $this->paymentMethodService->findAll()
        );
    } //index

    public function show(PaymentMethod $payment_method)
    {
        return new PaymentMethodResource($payment_method);
    } //show

    public function store(CreateRequest $request)
    {
        $this->paymentMethodService->create($request);
        return response('Метод оплаты добавлен в базу данных', 201);
    } //store

    public function update(UpdateRequest $request, PaymentMethod $payment_method)
    {
        $this->paymentMethodService->update(request: $request, paymentMethod: $payment_method);
        return response('Метод оплаты обновлён', 200);
    } //update

    public function destroy(PaymentMethod $payment_method)
    {
        $this->paymentMethodService->remove($payment_method);
    } //destory
}
