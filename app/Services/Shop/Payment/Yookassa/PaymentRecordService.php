<?php

namespace App\Services\Shop\Payment\Yookassa;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\ReadRepository\Shop\Payment\Yookassa\PaymentRecordReadRepository;
use App\Repository\Shop\Payment\Yookassa\PaymentRecordRepository;
use Illuminate\Support\Facades\Http;

class PaymentRecordService{
    public function __construct(
        private PaymentRecordRepository $repository,
        private PaymentRecordReadRepository $readRepository
    )
    {} //Конструктор

    private function makeRequest(string $idempotenceKey, Order $order, YookassaShop $shop): array //Сделать Http-запрос на ЮKassa
    {
        $response = Http::
            timeout(3)
            ->retry(3, 500)
            ->withHeaders(['Idempotence-Key' => $idempotenceKey, 'Content-Type' => 'application/json'])
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

            $response->throw(); //Выбросить исключение, если запрос не удался

            return $response->json();
    } //makeRequest

    public function create(Order $order, YookassaShop $shop): string //Зарегистрировать попытку платежа и создать объект платежа в ЮKassa
    {
        //Проверка наличия попытки платежа по этому заказу
        //
        //...
        //

        //Регистрация попытки платежа
        $record = $this->repository->register($order->id);

        //Запрос на ЮKassa
        $data = $this->makeRequest(
            idempotenceKey: $record->idempotence_key,
            order: $order,
            shop: $shop
        );

        //Заполнение полученными данными
        $this->repository->fill(
            record: $record,
            response: $data
        );

    } //create
};