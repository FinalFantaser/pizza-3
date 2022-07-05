<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Shop\City;
use App\Http\Requests\Api\Admin\Manager\AssignRequest;
use App\Http\Requests\Api\Admin\Manager\DismissRequest;

use App\Repository\User\ManagerRepository;
use App\ReadRepository\User\ManagerReadRepository;

class ManagerService{
    private $repository;
    private $readRepository;

    public function __construct(ManagerRepository $repository, ManagerReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function assign(AssignRequest $request){
        $this->repository->assign(
            User::findOrFail($request->user_id),
            City::findOrFail($request->city_id)
        );
    } //assign

    public function dismiss(DismissRequest $request){
        $this->repository->dismiss(
            User::findOrFail($request->user_id)
        );
    } //assign

    public function findAll(){
        return $this->readRepository->findAll();
    } //findAll

    public function findByCity(City $city){
        return $this->readRepository->findByCity($city);
    } //findByCity
}