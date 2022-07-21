<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Delivery;

use App\Http\Controllers\Controller;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryMethod\UpdateRequest;
use App\Http\Resources\Delivery\DeliveryMethodResource;
use App\Services\Shop\Delivery\DeliveryMethodService;

class DeliveryMethodController extends Controller
{
    private $service;

    public function __construct(DeliveryMethodService $service){
        $this->service = $service;
    } //Конструктор

    public function index(){
        return DeliveryMethodResource::collection(
            $this->service->getMethods()->paginate(50)
        );
    } //index

    public function show(DeliveryMethod $method){
        return new DeliveryMethodResource($method);
    } //show

    public function store(CreateRequest $request){
        $this->service->create($request);
        return response('Метод доставки добавлен в базу данных', 201);
    } //store

    public function update(UpdateRequest $request, DeliveryMethod $method){
        $this->service->update($request, $method);
        return response('Данные метода доставки обновлены');
    } //update

    public function destroy(DeliveryMethod $method){
        $this->service->remove($method);

        return response('Метод доставки удалён из базы данных', 204);
    } //destroy
}
