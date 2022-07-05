<?php

namespace App\Services\User;

use App\Http\Requests\Api\UsersRequest;

use App\Models\User;

use App\Repository\User\UserRepository;
use App\ReadRepository\User\UserReadRepository;

class UserService{
    private $repository;
    private $readRepository;

    public function __construct(UserRepository $repository, UserReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(UsersRequest $request){
        $this->repository->create($request->name, $request->email, $request->password);
    } //create

    public function update(UsersRequest $request, User $user){
        $this->repository->update($user, $request->name, $request->email);
    } //create

    public function remove(User $user){
        $this->repository->remove($user);
    } //remove

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods

    public function findById(int $id){
        return $this->readRepository->findById($id);
    }
}