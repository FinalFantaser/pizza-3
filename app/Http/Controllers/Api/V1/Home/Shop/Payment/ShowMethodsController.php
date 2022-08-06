<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentMethodResource;
use App\Services\Shop\Payment\PaymentMethodService;

class ShowMethodsController extends Controller
{
    public function __construct(
        private PaymentMethodService $service
    )
    {} //Конструктор

    public function __invoke()
    {
        return PaymentMethodResource::collection(
            $this->service->findAll()
        );
    }
}
