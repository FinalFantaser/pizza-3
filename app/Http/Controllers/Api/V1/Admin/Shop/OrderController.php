<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Events\Order\OrderComplete;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Http\Requests\Api\Admin\Shop\Order\PayRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Payments\Record;
use App\Services\Shop\OrderService;
use App\Services\Shop\Payment\PaymentMethodService;
use DomainException;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentMethodService $paymentService
    )
    {} //Конструктор

    //
    //                  REST-API
    //
    public function index(){
        return OrderResource::collection(
            $this->orderService->getMethods()->paginate(20)
        );
    } //index

    public function show(Order $order){
        return new OrderResource($order->load(['deliveryMethod', 'customerData', 'customerData.city', 'pickupPoint', 'items', 'payment']));
    } //show

    public function destroy(Order $order){
        $this->orderService->remove($order);
        return response()->json(['message' => 'Заказ удалён']);
    } //destroy

    //
    //                  Управление заказами
    //    
    public function cancel(CancelRequest $request, Order $order)
    {
        $this->orderService->cancelByAdmin($request, $order);
        return response()->json(['message' => 'Заказ отменён']);
    } //cancel

    public function pay(PayRequest $request) //TODO Переделать под новую систему
    {
        $order = $this->orderService->findById($request->order_id);
        $method = $this->paymentService->findByCode($request->code);

        if($request->payer === Record::PAYER_ADMIN)
            $this->orderService->payByAdmin(order: $order, paymentMethod: $method, changeSum: $request->change_sum ?? 0);
        elseif($request->payer === Record::PAYER_CUSTOMER)
            $this->orderService->payByCustomer(order: $order, paymentMethod: $method, changeSum: $request->change_sum ?? 0);
        else
            throw new DomainException(message: 'Плательщик отсутствует в базе');

        return response()->json(['message' => 'Заказ оплачен']);
    } //pay

    public function send(Order $order)
    {
        $this->orderService->makeSent($order);
        return response()->json(['message' => 'Заказ отправлен']);
    } //sent

    public function complete(Order $order)
    {
        $this->orderService->makeCompleted($order);
        OrderComplete::dispatch($order);
        return response()->json(['message' => 'Заказ выполнен']);
    } //complete
}
