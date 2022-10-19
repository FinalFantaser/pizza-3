<?php

namespace App\Rules\Order;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Payment\PaymentMethod;
use App\ReadRepository\Shop\Delivery\DeliveryMethodReadRepository;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class CompliesWithDelivery implements DataAwareRule, InvokableRule
{
    protected $data = [];
    private DeliveryMethodReadRepository $repository;

    public function setData($data){
        $this->data = $data;
        $this->repository = new DeliveryMethodReadRepository;
    } //setData


    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $check = [];

        $deliveryMethod = $this->data['delivery_method'];

        if($deliveryMethod === DeliveryMethod::TYPE_PICKUP)
            $check = [PaymentMethod::CODE_CASH_PICKUP, PaymentMethod::CODE_CARD_PICKUP, PaymentMethod::CODE_ONLINE_PICKUP];
        elseif($deliveryMethod === DeliveryMethod::TYPE_COURIER)
            $check = [PaymentMethod::CODE_CASH_DELIVERY, PaymentMethod::CODE_CARD_DELIVERY, PaymentMethod::CODE_ONLINE_PICKUP];

        if( !in_array(needle: $value, haystack: $check) )
            $fail('Метод оплаты не соответствует методу доставки');
    }
}
