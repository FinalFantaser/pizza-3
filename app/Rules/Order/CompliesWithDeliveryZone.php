<?php

namespace App\Rules\Order;

use App\Models\Shop\Delivery\DeliveryZone;
use App\ReadRepository\Shop\Delivery\DeliveryZoneReadRepository;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class CompliesWithDeliveryZone implements DataAwareRule, InvokableRule
{
    protected $data = [];
    private DeliveryZoneReadRepository $repository;

    public function setData($data){
        $this->data = $data;
        $this->repository = new DeliveryZoneReadRepository;
    } //setData


    public function __invoke($attribute, $value, $fail)
    {
        $deliveryZone = $this->repository->findById( $this->data['delivery_zone_id'] );

        if($value != $deliveryZone->city_id)
            $fail('Указанный город не соответствует зоне доставки');

    }
}
