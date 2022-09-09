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
            $this->orderService->getMethods()
                ->with(['customerData:id,name,phone,actual_city,city_id', 'customerData.city:id,name'])
                ->paginate(20)
        );
    } //index

    public function show(Order $order){
        $this->orderService->makeViewed($order);
        return new OrderResource($order->load(['customerData', 'customerData.city', 'pickupPoint', 'items', 'payment', 'deliveryZone']));
    } //show

    public function findUnviewed()
    {
        return OrderResource::collection(
            $this->orderService->findUnviewed()
        );
    } //findUnviewed

    public function destroy(Order $order){
        $this->orderService->remove($order);
        return response()->json(['message' => 'Заказ удалён']);
    } //destroy

    //
    //                  Управление заказами
    //    
    public function cancel(CancelRequest $request, Order $order)
    {
        $this->orderService->makeViewed($order);
        $this->orderService->cancelByAdmin($request, $order);
        return response()->json(['message' => 'Заказ отменён']);
    } //cancel

    public function pay(PayRequest $request)
    {
        $order = $this->orderService->findById($request->order_id);
        $this->orderService->makeViewed($order);
        $method = $this->paymentService->findByCode($request->code);

        if($request->payer === Record::PAYER_ADMIN)
            $this->orderService->payByAdmin(order: $order, paymentMethod: $method, changeSum: $request->change_sum ?? 0);
        elseif($request->payer === Record::PAYER_CUSTOMER)
            $this->orderService->payByCustomer(order: $order, paymentMethod: $method, changeSum: $request->change_sum ?? 0);
        else
            throw new DomainException(message: 'Плательщик отсутствует в базе');

        return response('Заказ оплачен');
    } //pay

    public function send(Order $order)
    {
        $this->orderService->makeViewed($order);
        $this->orderService->makeSent($order);
        return response('Заказ отправлен');
    } //sent

    public function complete(Order $order)
    {
        $this->orderService->makeViewed($order);
        $this->orderService->makeCompleted($order);
        OrderComplete::dispatch($order);
        return response('Заказ выполнен');
    } //complete

    public function makeViewed(Order $order)
    {
        $this->orderService->makeViewed($order);
        return response('Заказ помечен как просмотренный');
    }
}
