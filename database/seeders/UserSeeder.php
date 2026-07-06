<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@erp.test'],
            [
                'name' => 'Admin ERP',
                'password' => 'password',
                'role' => UserRole::ADMIN,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@erp.test'],
            [
                'name' => 'Staff ERP',
                'password' => 'password',
                'role' => UserRole::STAFF,
            ]
        );
    }
}
