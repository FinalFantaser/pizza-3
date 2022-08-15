<?php

namespace App\Services\Shop\Delivery;

use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryZone\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryZone\UpdateRequest;
use App\Models\Shop\City;
use App\Models\Shop\Delivery\DeliveryZone;
use App\ReadRepository\Shop\CityReadRepository;
use App\ReadRepository\Shop\Delivery\DeliveryZoneReadRepository;
use App\Repository\Shop\Delivery\DeliveryZoneRepository;
use Illuminate\Database\Eloquent\Collection;

class DeliveryZoneService{
    public function __construct(
        private DeliveryZoneRepository $deliveryZoneRepository,
        private DeliveryZoneReadRepository $deliveryZoneReadRepository,
        private CityReadRepository $cityReadRepository
    )
    {} //Конструктор

    //
    //      CRUD-методы
    //

    public function create(CreateRequest $request): DeliveryZone
    {
        return $this->deliveryZoneRepository->create(
            city_id: $request->city_id,
            restaurant_id: $request->restaurant_id,
            code: $request->code,
            name: $request->name,
            sum_min:  $request->sum_min,
            time_min: $request->time_min,
            time_max:  $request->time_max,
            delivery_price: $request->delivery_price,
            sum_for_free: $request->sum_for_free,
            coordinates: $request->coordinates
        );
    } //create

    public function update(UpdateRequest $request, DeliveryZone $deliveryZone): DeliveryZone
    {
        return $this->deliveryZoneRepository->update(
            deliveryZone: $deliveryZone,
            city_id: $request->city_id,
            restaurant_id: $request->restaurant_id,
            code: $request->code,
            name: $request->name,
            sum_min:  $request->sum_min,
            time_min: $request->time_min,
            time_max:  $request->time_max,
            delivery_price: $request->delivery_price,
            sum_for_free: $request->sum_for_free,
            coordinates: $request->coordinates
        );
    } //update

    public function remove(DeliveryZone $deliveryZone): void
    {
        $this->deliveryZoneRepository->remove($deliveryZone);
    } //remove

    //
    //      Запросы для поиска
    //
    public function getMethods()
    {
        return $this->deliveryZoneReadRepository->getMethods();
    } //getMethods

    public function findByCityId(City|int $city, string|array $with = null): Collection
    {
        return $this->deliveryZoneReadRepository->findByCity(
            city: $city,
            with: $with
        );
    } //findByCityId

    public function findByCitySlug(string $slug, string|array $with = null): Collection
    {
        return $this->deliveryZoneReadRepository->findByCity(
            city: $this->cityReadRepository->findBySlug($slug),
            with: $with
        );
    } //findByCitySlug
};