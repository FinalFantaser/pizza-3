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
            title: $request->title,
            code: $request->code,
        );
    } //create

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

    public function findByCode(string $name): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode($name);
    } //findByName

    public function findCash(): PaymentMethod //Найти способ оплаты наличными
    { 
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CASH );
    } //findCash

    public function findCard(): PaymentMethod //Найти способ оплаты картой
    { 
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CARD );
    } //findCard

    public function findOnline(): PaymentMethod //Найти способ оплаты онлайн
    { 
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_ONLINE );
    } //findOnline

    public function findCashPickup(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CASH_PICKUP );
    } //findCashPickup

    public function findCashDelivery(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CASH_DELIVERY );
    } //findCashDelivery

    public function findCardPickup(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CARD_PICKUP );
    } //findCardPickup

    public function findCardDelivery(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_CARD_DELIVERY );
    } //findCardDelivery

    public function findOnlinePickup(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_ONLINE_PICKUP );
    } //findCardPickup

    public function findOnlineDelivery(): PaymentMethod
    {
        return $this->paymentMethodReadRepository->findByCode( PaymentMethod::CODE_ONLINE_DELIVERY );
    } //findCardDelivery
};