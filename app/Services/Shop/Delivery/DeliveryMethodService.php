<?php

namespace App\Services\Shop\Delivery;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryMethod\UpdateRequest;
use App\Repository\Shop\Delivery\DeliveryMethodRepository;
use App\ReadRepository\Shop\Delivery\DeliveryMethodReadRepository;

class DeliveryMethodService{
    private $repository;
    private $readRepository;

    public function __construct(DeliveryMethodRepository $repository, DeliveryMethodReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(CreateRequest $request){        
        return 
            $this->repository->create(
                name: $request->name,
                type: $request->type,
                cost: $request->cost, 
                freeFrom: $request->free_from,
                minWeight: $request->min_weight,
                maxWeight: $request->max_weight
            );
    } //create

    public function update(UpdateRequest $request, DeliveryMethod $method){
        return
            $this->repository->update(
                deliveryMethod: $method,
                name: $request->name,
                type: $request->type,
                cost: $request->cost, 
                freeFrom: $request->free_from,
                minWeight: $request->min_weight,
                maxWeight: $request->max_weight);
    } //update

    public function remove(DeliveryMethod $method){
        $this->repository->remove($method);
    } //remove

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods

    public function findById(int $id){
        return $this->readRepository->findById($id);
    } //findById
}