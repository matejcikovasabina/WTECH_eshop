<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // ADMIN
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole->id,
        ]);

        // USER
        User::create([
            'first_name' => 'User',
            'last_name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('user1234'),
            'role_id' => $userRole->id,
        ]);
    }
}