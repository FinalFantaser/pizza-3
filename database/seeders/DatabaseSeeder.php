<?php

namespace Database\Seeders;

use App\Models\MediaLibrary;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Roles
        //Role::firstOrCreate(['name' => Role::ROLE_EDITOR]);
        $role_admin = Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);


        // MediaLibrary
        MediaLibrary::firstOrCreate([]);

        // Users
        $user = User::firstOrCreate(
            ['email' => '1@1.ru'],
            [
                'name' => 'pizza_admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );

        $user->roles()->sync([$role_admin->id]);

    }
}
