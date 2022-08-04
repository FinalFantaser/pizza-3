<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Payment\YookassaPaymentRequest;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\YookassaShopService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;

class YookassaController extends Controller
{
    public function __construct(
        private YookassaShopService $yookassaShopService,
        private OrderService $orderService
    )
    {} //Конструктор

    public function __invoke(YookassaPaymentRequest $request)
    {
        //Загрузка данных
        $order = $this->orderService->findByToken(
            token: $request->order_token,
            with: ['customerData']
        );
        if($order->paid)
            return response('Заказ уже оплачен');

        $shop = $this->yookassaShopService->findByCity($order->customerData->city_id);

        return response()->json($shop);

        //Отправка запроса
        $response = Http::
            // async()
            withHeaders(['Idempotence-Key' => Str::random(64), 'Content-Type' => 'application/json']) //TODO Разобраться с Idempotence-Key, он необходим
            ->withBasicAuth($shop->shop_id, $shop->api_token)
            ->post(YookassaShop::PAYMENT_URL, [
                'amount' => [
                    'value' => $order->cost,
                    'currency' => 'RUB'
                ],
                'capture' => false,
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $shop->returnUrl($order->token),
                ],
                'description' => "Оплата за заказ №".$order->id
            ]);
            // ->then(function ($obtainedResponse){
            //     return $obtainedResponse;
            // });
        return $response->json();
    }
}
