<?php

namespace App\Services\Shop;

use App\Http\Requests\Api\Admin\Shop\City\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\City\UpdateRequest;

use App\Models\Shop\City;

use App\Repository\Shop\CityRepository;
use App\ReadRepository\Shop\CityReadRepository;

class CityService{
    private $repository;
    private $readRepository;

    public function __construct(CityRepository $repository, CityReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(CreateRequest $request){        
        $this->repository->create($request->name);
    } //create

    public function update(UpdateRequest $request, City $city){
        $this->repository->update($city, $request->name);
    } //update

    public function remove(City $city){
        $this->repository->remove($city);
    } //remove

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods
}