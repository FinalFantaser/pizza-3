<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryZone\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryZone\UpdateRequest;
use App\Http\Resources\Delivery\DeliveryZoneResource;
use App\Http\Resources\Delivery\DeliveryZoneResourceShort;
use App\Models\Shop\City;
use App\Models\Shop\Delivery\DeliveryZone;
use App\Services\Shop\Delivery\DeliveryZoneService;

class DeliveryZoneController extends Controller
{
    public function __construct(
        private DeliveryZoneService $service
    )
    {} //Конструктор

    public function index()
    {
        return DeliveryZoneResourceShort::collection(
            $this->service->getMethods()->with('city')->get()
        );
    }


    public function store(CreateRequest $request)
    {
        $this->service->create($request);
        return response(content: 'Зона доставки добавлена в базу данных', status: 201);
    }


    public function show(DeliveryZone $zone)
    {
        return (new DeliveryZoneResource(
            $zone->load('city')
        ))->additional(['full' => 1]);
    }

    public function update(UpdateRequest $request, DeliveryZone $zone)
    {
        $this->service->update(request: $request, deliveryZone: $zone);
        return response(content: 'Зона доставки обновлена', status: 200);
    }

    public function destroy(DeliveryZone $zone)
    {
        $this->service->remove($zone);
        return response(content: 'Заона доставки удалена', status: 204);
    }

    public function byCity(string $city)
    {
        return DeliveryZoneResourceShort::collection(
            $this->service->findByCitySlug(slug: $city, with: 'city')
        );
    }
}
