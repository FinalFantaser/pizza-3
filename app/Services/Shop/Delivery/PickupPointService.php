<?php

namespace App\Services\Shop\Delivery;

use App\Http\Requests\Api\Admin\Shop\Delivery\PickupPoint\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\PickupPoint\UpdateRequest;
use App\Models\Shop\Delivery\PickupPoint;
use App\ReadRepository\Shop\Delivery\PickupPointReadRepository;
use App\Repository\Shop\Delivery\PickupPointRepository;

class PickupPointService{
    public function __construct(
        private PickupPointRepository $pickupPointRepository,
        private PickupPointReadRepository $pickupPointReadRepository
    )
    {} //Конструктор
    
    //
    //  CRUD-методы
    //
    public function create(CreateRequest $request): PickupPoint
    {
        return $this->pickupPointRepository->create(city_id: $request->city_id, address: $request->address);
    } //create

    public function update(UpdateRequest $request, PickupPoint $pickupPoint): PickupPoint
    {
        return $this->pickupPointRepository->update(pickupPoint: $pickupPoint, city_id: $request->city_id, address: $request->address);
    } //update

    public function remove(PickupPoint $pickupPoint): void
    {
        $this->pickupPointRepository->remove($pickupPoint);
    } //remove

    //
    //  Методы для поиска
    //
    public function getMethods()
    {
        return $this->pickupPointReadRepository->getMethods();
    } //getMethods

    public function findById(int $id): PickupPoint
    {
        return $this->pickupPointReadRepository->findById($id);
    } //findById

    public function findByCity(int $city_id)
    {
        return $this->pickupPointReadRepository->findByCity($city_id);
    } //findByCity
};