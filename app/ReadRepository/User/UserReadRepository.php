<?php

namespace App\ReadRepository\User;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserReadRepository
{
    public function findById($id): ?User
    {
        $user = User::findOrFail($id);
        return $user;
    } //find

    public function findByToken(string $token): ?User
    {
        $user = User::where('verify_token', $token)->first();
        return $user;
    } //findByToken

    public function findByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();
        return $user;
    } //findByEmail

    public function getMethods()
    {
        $users = User::orderByDesc('id');
        return $users;
    } //findAll

    public function getToken($email, $password){
        //TODO Наладить работоспособность метода
        $data = User::where(['email' => $email, 'password' => $password])->get('api_token');
        return  $data['api_token'] ?? null;
    } //getToken
}
