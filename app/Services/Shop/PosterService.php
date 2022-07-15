<?php

namespace App\Services\Shop;

use App\Http\Requests\Api\Admin\Shop\Poster\AttachToCityRequest;
use App\Models\Shop\City;
use App\Models\Shop\Poster;
use App\Repository\Shop\PosterRepository;
use App\ReadRepository\Shop\PosterReadRepository;
use App\Http\Requests\Api\Admin\Shop\Poster\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Poster\UpdateRequest;

class PosterService{
    private $repository;
    private $readRepository;

    public function __construct(PosterRepository $repository, PosterReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(CreateRequest $request){
        return
            $this->repository->create(
                $request->name,
                $request->description,
//                $request->enabled,
                filter_var( $request->enabled, FILTER_VALIDATE_BOOLEAN ),
                $request->has('city_id') ? $request->city_id : null
            );
    } //create

    public function update(UpdateRequest $request, Poster $poster){
        return
            $this->repository->update(
                $poster,
                $request->name,
                $request->description,
//                $request->enabled,
                filter_var( $request->enabled, FILTER_VALIDATE_BOOLEAN ),
                $request->has('city_id') ? $request->city_id : null
            );
    } //update

    public function remove(Poster $poster){
        $this->repository->remove($poster);
    } //remove

    public function enable(Poster $poster){
        $this->repository->enable($poster);
    } //poster

    public function disable(Poster $poster){
        $this->repository->disable($poster);
    } //poster

    public function attachToCity(AttachToCityRequest $request){
        $this->repository->attachToCity(
            Poster::findOrFail($request->poster_id),
            $request->city_id
        );
    } //attachToCity

    public function detachFromCity(AttachToCityRequest $request){
        $this->repository->detachFromCity(
            Poster::findOrFail($request->poster_id),
            $request->city_id
        );
    } //detachFromCity

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods

    public function findByCity(City $city){
        return $this->readRepository->findByCity($city);
    } //findByCityAndCategory


}
