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
            ['email' => 'imis@ndmu.edu.ph'],
            [
                'name' => 'IT Admin',
                'first_name' => 'MIS',
                'last_name' => 'Admin',
                'middle_name' => '',
                'password' => Hash::make('@m1s@Office'),
                'role' => 'it_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'alumni@ndmu.edu.ph'],
            [
                'name' => 'Alumni Officer',
                'first_name' => 'Alumni',
                'last_name' => 'Officer',
                'middle_name' => '',
                'password' => Hash::make('@Alumni123@Office'),
                'role' => 'alumni_officer',
            ]
        );
    }
}
