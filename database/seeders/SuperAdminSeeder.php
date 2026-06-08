<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate(
            ['email' => 'gdejesugarcia53@gmail.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'gdejesugarcia53@gmail.com',
                'password' => Hash::make('prueba0123'),
                'role'     => 'superadmin',
            ]
        );
    }
}
