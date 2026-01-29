<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => 'admin',
            'role' => UserRole::ADMIN->value,
        ]);

        User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@mail.com',
            'password' => 'moderator',
            'role' => UserRole::MODERATOR->value,
        ]);

        User::create([
            'name' => 'Member User',
            'email' => 'member@mail.com',
            'password' => 'member',
            'role' => UserRole::MEMBER->value,
        ]);
    }
}
