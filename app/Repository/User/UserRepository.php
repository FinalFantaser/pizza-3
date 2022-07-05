<?php

namespace App\Repository\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'api_token' => \App\Models\Token::generate(),
        ]);

        return $user;
    }

    public function update(User $user, string $name, string $email): void
    {
        $user->update([
            'name' => $name,
            'email' => $email
        ]);
    }

    public function rename(User $user, string $name): void
    {
        $user->update([
            'name' => $name
        ]);
    }

    public function remove(User $user): void
    {
        $user->delete();
    }

    public function verify(User $user): void
    {
        $user->verify();
    }

    public function draft(User $user): void
    {
        $user->draft();
    }

    public function addPhoto(User $user, $photo): void
    {
        if ($user->getFirstMedia('avatar')) {
            $user->getFirstMedia('avatar')->delete();
        }
        $user->addMediaFromRequest('file')->toMediaCollection('avatar');

    }
}
