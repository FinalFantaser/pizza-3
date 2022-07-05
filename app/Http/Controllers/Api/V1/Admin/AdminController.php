<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\User\AdminService;
use App\Services\User\UserService;
use App\Http\Requests\Api\Admin\AdminRequest;

class AdminController extends Controller
{
    private $userService;
    private $adminService;

    public function __construct(UserService $userService, AdminService $adminService){
        $this->userService = $userService;
        $this->adminService = $adminService;
    } //Конструктор

    public function index(){
        return response()->json($this->adminService->getMethods()->get());
    } //index

    public function assign(AdminRequest $request){
        $this->adminService->assign(
            $this->userService->findById($request->user_id)
        );

        return response()->json(['message' => 'Пользователь назначен администратором']);
    } //assign

    public function dismiss(AdminRequest $request){
        $this->adminService->dismiss(
            $this->userService->findById($request->user_id)
        );

        return response()->json(['message' => 'С пользователя сняты полномочия администратора']);
    } //dismiss
}
