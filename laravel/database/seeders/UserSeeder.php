<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $userRole = Role::where('name', 'user')->firstOrFail();

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'password' => 'admin1234',
                'role_id' => $adminRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'first_name' => 'User',
                'last_name' => 'User',
                'password' => 'user1234',
                'role_id' => $userRole->id,
            ]
        );
    }
}