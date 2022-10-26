<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order\Order;
use App\Services\EmailService;
use App\Services\SettingsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    private SettingsService $service;

    public function __construct(private EmailService $emailService)
    {
        $this->service = SettingsService::getInstance();
    } //Конструктор

    public function test()
    {
        // return response()->json(Config::get('mail.mailers.dynamic_smtp', 'Не найдено'));
        
        $order = Order::latest()->first();
        $this->emailService->send($order);

        return response('Сделана попытка отправки заказа #' . $order->id);
    } //test

    public function index()
    {
        return response()->json($this->service->getAll());
    } //test

    public function update(Request $request)
    {
        //TODO Валидация
        
        $this->service->setMassive($request->all());
        return response('Настройки обновлены');
    } //update

    public function reset()
    {
        $this->service->reset();
        return response('Выставлены настройки по умолчанию');
    } //reset
}
