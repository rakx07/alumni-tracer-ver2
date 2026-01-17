<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'itadmin@ndmu.edu.ph'],
            [
                'name' => 'IT Admin',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'it_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'alumni.officer@ndmu.edu.ph'],
            [
                'name' => 'Alumni Officer',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'alumni_officer',
            ]
        );
    }
}
