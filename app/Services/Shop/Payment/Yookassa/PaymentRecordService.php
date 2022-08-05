<?php

namespace App\Services\Shop\Payment\Yookassa;

use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\ReadRepository\Shop\Payment\Yookassa\PaymentRecordReadRepository;
use App\Repository\Shop\Payment\Yookassa\PaymentRecordRepository;
use DomainException;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\Response;

class PaymentRecordService{
    public function __construct(
        private PaymentRecordRepository $repository,
        private PaymentRecordReadRepository $readRepository
    )
    {} //Конструктор

    private function checkPreviousPayments(Order $order): PaymentRecord //Проверка предыдущих платежей по заказу. Если заказ не был оплачен, и предыдущая запись не истекла, возвращает её
    {
        //Отмечен ли сам заказ как оплаченный?
        if($order->isPaid())
            throw new DomainException(message: 'Заказ №'.$order->id.' уже оплачен');

        //Есть ли успешные платежи по заказу?
        if( $this->readRepository->orderisPaid($order->id) )
            throw new DomainException(message: 'Заказ №'.$order->id.' уже оплачен');
        
        //Есть ли незавершённые и не истёкшие попытки платежей?
        return $this->readRepository->findLatestPending($order->id);
    } //checkPreviousPayments

    private function requestPayment(string $idempotenceKey, Order $order, YookassaShop $shop): Response //Запросить создание платежа
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

            return $response;
    } //requestPayment

    private function requestStatus(PaymentRecord $record): Response //Запросить статус платежа
    {
        $response = Http::
            timeout(3)
            ->retry(3, 500)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBasicAuth($record->shop->shop_id, $record->shop->api_token)
            ->get(PaymentRecord::CHECK_STATUS_URL, [
                'payment_id' => $record->payment_id,
            ]);

            $response->throw(); //Выбросить исключение, если запрос не удался

            return $response;
    } //requestStatus
    

    public function create(Order $order, YookassaShop $shop): string //Зарегистрировать попытку платежа и создать объект платежа в ЮKassa
    {
        //Валидация оплаты по заказу
        $record = $this->checkPreviousPayments($order);
        
        //Регистрация попытки платежа
        if(is_null($record)) //Если предыдущих попыток платежа нет, или они истекли, зарегистрировать новую
            $record = $this->repository->register(order_id: $order->id, shop_id: $shop->id); 


        //Запрос на ЮKassa
        $response = $this->requestPayment(
            idempotenceKey: $record->idempotence_key,
            order: $order,
            shop: $shop
        );

        //Заполнение полученными данными
        $this->repository->fill(
            record: $record,
            response: $response
        );

        //Проверка валидности записи
        $record->validate();
        
        //Возвращение ссылки на страницу оплаты
        return $response['confirmation']['confirmation_url'];
    } //create

    public function check(Order $order) //Проверяет, прошёл ли платёж
    {
        //Загрузить последнюю запись о платеже по заказу
        $record = $this->readRepository->findLatestRecord(order_id: $order->id, with: 'shop');
        
        //Отправить запрос по идентификатору платежа
        $response = $this->requestStatus($record);

        //Заполнение полученными данными
        $this->repository->fill(
            record: $record,
            response: $response
        );

        //Проверить статус
        if($record->isPaid() || $record->isSucceeded())
            $order->update(['paid' => true]);
    } //check
};