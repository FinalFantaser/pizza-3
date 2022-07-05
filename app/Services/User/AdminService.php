<?php

namespace App\Services\User;

use App\Models\User;

use App\Repository\User\AdminRepository;
use App\ReadRepository\User\AdminReadRepository;

class AdminService{
    private $repository;
    private $readRepository;

    public function __construct(AdminRepository $repository, AdminReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function assign(User $user){
       $this->repository->assign($user);
    } //assign

    public function dismiss(User $user){
        $this->repository->dismiss($user);
    } //dismiss

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods
}