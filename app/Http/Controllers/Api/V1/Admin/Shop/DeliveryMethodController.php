<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;

use App\Models\Shop\DeliveryMethod;
use App\Http\Requests\Api\Admin\Shop\DeliveryMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\DeliveryMethod\UpdateRequest;
use App\Http\Resources\DeliveryMethodResource;
use App\Services\Shop\DeliveryMethodService;

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

    public function show(DeliveryMethod $delivery_method){
        return new DeliveryMethodResource($delivery_method);
    } //show

    public function store(CreateRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Метод доставки добавлен в базу данных']);
    } //store

    public function update(UpdateRequest $request, DeliveryMethod $delivery_method){
        $this->service->update($request, $delivery_method);
        return response()->json(['message' => 'Данные метода доставки обновлены']);
    } //update

    public function destroy(DeliveryMethod $delivery_method){
        $this->service->remove($delivery_method);

        return response()->json(['message' => 'Метод доставки удалён из базы данных']);
    } //destroy
}
