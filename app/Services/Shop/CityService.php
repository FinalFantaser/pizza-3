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

    //
    // CRUD-методы
    //
    public function create(CreateRequest $request){        
        return $this->repository->create(
            name: $request->name,
            address: $request->address,
            phone:  $request->phone
        );
    } //create

    public function update(UpdateRequest $request, City $city){
        return $this->repository->update(
            city: $city,
            name: $request->name,
            address: $request->address,
            phone:  $request->phone
        );
    } //update

    public function remove(City $city){
        $this->repository->remove($city);
    } //remove


    //
    // Загрузка моделей
    //
    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods

    public function findAll(){
        return $this->readRepository->findAll();
    } //findAll

    public function findById(int $id){
        return $this->readRepository->findById($id);
    } //findById

    public function findBySlug(string $slug){
        return $this->readRepository->findBySlug($slug);
    } //findBySlug
}