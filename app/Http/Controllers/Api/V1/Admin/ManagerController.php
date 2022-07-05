<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\Manager\AssignRequest;
use App\Http\Requests\Api\Admin\Manager\DismissRequest;

use App\Models\User;
use App\Models\Shop\City;

use App\Services\User\ManagerService;

class ManagerController extends Controller
{
    private $service;

    public function __construct(ManagerService $service){
        $this->service = $service;
    } //Конструктор

    public function index(){
        $query = $this->service->findAll();
        return response()->json($query->orderBy('city')->get());
    } //index

    public function assign(AssignRequest $request){
        $this->service->assign($request);
        return response()->json(['message' => 'Пользователь назначен менеджером']);
    } //assign

    public function dismiss(DismissRequest $request){
        $this->service->dismiss($request);
        return response()->json(['message' => 'С пользователя сняты полномочия менеджера']);
    } //dismiss
}
