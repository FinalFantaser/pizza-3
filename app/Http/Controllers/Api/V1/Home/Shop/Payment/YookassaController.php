<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Home\Shop\Payment\YookassaPaymentRequest;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payment\Yookassa\PaymentRecord;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\Yookassa\PaymentRecordService;
use App\Services\Shop\Payment\Yookassa\YookassaShopService;

class YookassaController extends Controller
{
    public function __construct(
        private YookassaShop $shop,
        private Order $order,
        private YookassaShopService $yookassaShopService,
        private OrderService $orderService,
        private PaymentRecordService $paymentRecordService
    )
    {
        //Загрузка данных
        $request = request();
        if( !$request->has('order_token') )
            return;

        $this->order = $this->orderService->findByToken(
                    token: $request->order_token,
                    with: 'customerData'
                );
        $this->shop = $this->yookassaShopService->findByCity($this->order->customerData->city_id);
    } //Конструктор

    public function __invoke(YookassaPaymentRequest $request){
        $link = $this->paymentRecordService->create(order: $this->order, shop: $this->shop);
        return response($link, 200);
    }
}
