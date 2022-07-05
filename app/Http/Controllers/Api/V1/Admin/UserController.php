<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\UsersRequest;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Services\User\UserService;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service){
        $this->service = $service;
    } //Конструктор

    public function index(Request $request)
    {
        $query = $this->service->getMethods();
        return UserResource::collection( $query->with('roles')->latest()->paginate($request->input('limit', 20)) );
    } //index

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    } //show

    public function store(UsersRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Пользователь создан']);
    } //store

    public function update(UsersRequest $request, User $user)
    {
        $this->service->update($request, $user);
        return response()->json(['message' => 'Данные пользователя обновлены']); 
    } //update

    public function destroy(User $user){
        $this->service->remove($user);
        return response()->json(['message' => 'Пользователь удалён']);
    } //destroy
}
