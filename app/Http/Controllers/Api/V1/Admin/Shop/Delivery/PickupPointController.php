<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Delivery\PickupPoint\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\PickupPoint\UpdateRequest;
use App\Http\Resources\Delivery\PickupPointResource;
use App\Models\Shop\Delivery\PickupPoint;
use App\Services\Shop\Delivery\PickupPointService;

class PickupPointController extends Controller
{
    public function __construct(
        private PickupPointService $pickupPointService
    )
    {} //Конструктор

    public function index()
    {
        return PickupPointResource::collection(
            $this->pickupPointService->getMethods()->with('city')->paginate(50)
        );
    } //index

    public function store(CreateRequest $request)
    {
        $this->pickupPointService->create($request);
        return response('Пункт самовывоза добавлен', 201);
    } //store

    public function show(int $pickup_point)
    {
        return new PickupPointResource(
            $this->pickupPointService->findById($pickup_point)
        );
    } //show

    public function update(UpdateRequest $request, PickupPoint $pickup_point)
    {
        $this->pickupPointService->update(request: $request, pickupPoint: $pickup_point);
        return response('Пункт самовывоза обновлён');
    } //update

    public function destroy(PickupPoint $pickup_point)
    {
        $this->pickupPointService->remove($pickup_point);
        return response('Пункт самовывоза удалён', 204);
    } //destroy

    public function listByCity(int $city_id)
    {
        return PickupPointResource::collection(
            $this->pickupPointService->findByCity($city_id)
        );
    } //listByCity
}
