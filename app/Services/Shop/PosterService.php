<?php

namespace App\Services\Shop;

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
        $this->repository->create($request->name, $request->description, $request->enabled);
    } //create

    public function update(UpdateRequest $request, Poster $poster){
        $this->repository->update($poster, $request->name, $request->description, $request->enabled);
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

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods
}