<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\DeliveryMethodResource;
use App\Services\Shop\Delivery\DeliveryMethodService;

class ShowDeliveryMethodsController extends Controller
{
    public function __construct(
        private DeliveryMethodService $deliveryMethodService
    )
    {} //Конструктор

    public function __invoke()
    {
        return DeliveryMethodResource::collection(
            $this->deliveryMethodService->getMethods()->get()
        );
    }
}
