<?php

namespace App\Services\Shop\Payment;

use App\Http\Requests\Api\Admin\Shop\Payment\PaymentMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Payment\PaymentMethod\UpdateRequest;
use App\Models\Shop\Payment\PaymentMethod;
use App\ReadRepository\Shop\Payment\PaymentMethodReadRepository;
use App\Repository\Shop\Payment\PaymentMethodRepository;

class PaymentMethodService{
    public function __construct(
        private PaymentMethodRepository $paymentMethodRepository,
        private PaymentMethodReadRepository $paymentMethodReadRepository
    ){} //Конструктор

    //
    //  CRUD-методы
    //
    public function create(CreateRequest $request): PaymentMethod
    {
        return $this->paymentMethodRepository->create(
            name: $request->name,
            url: $request->url,
            api_key: $request->api_key,
            api_secret: $request->api_secret,
            additional: $request->additional
        );
    } //create

    public function update(UpdateRequest $request, PaymentMethod $paymentMethod): PaymentMethod
    {
        return $this->paymentMethodRepository->update(
            paymentMethod: $paymentMethod,
            name: $request->name,
            url: $request->url,
            api_key: $request->api_key,
            api_secret: $request->api_secret,
            additional: $request->additional
        );
    } //update

    public function remove(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethodRepository->remove($paymentMethod);
    } //remove

    //
    //  Методы для поиска
    //
    public function getMethods()
    {
        return $this->paymentMethodReadRepository->getMethods();
    } //getMethods

    public function findAll(string|array $with = null)
    {
        return $this->getMethods()
                ->when(function($query) use ($with){
                    return is_null($with)
                        ? $query
                        : $query->with($with);
                })
                ->get();
    } //findAll

    public function findById(int $id): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findById($id);
    } //findById

    public function findByName(string $name): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByName($name);
    } //findByName
};