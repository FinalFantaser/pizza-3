<?php

namespace App\Services\Shop;

use App\Models\Shop\DeliveryMethod;
use App\Http\Requests\Api\Admin\Shop\DeliveryMethod\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\DeliveryMethod\UpdateRequest;
use App\Repository\Shop\DeliveryMethodRepository;
use App\ReadRepository\Shop\DeliveryMethodReadRepository;

class DeliveryMethodService{
    private $repository;
    private $readRepository;

    public function __construct(DeliveryMethodRepository $repository, DeliveryMethodReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(CreateRequest $request){        
        return 
            $this->repository->create($request->name, $request->cost, $request->free_from, $request->min_weight, $request->max_weight);
    } //create

    public function update(UpdateRequest $request, DeliveryMethod $method){
        return
            $this->repository->update($method, $request->name, $request->cost, $request->free_from, $request->min_weight, $request->max_weight);
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