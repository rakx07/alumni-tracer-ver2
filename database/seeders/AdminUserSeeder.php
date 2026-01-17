<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ndmu.edu.ph'],
            [
                'name' => 'NDMU Admin',
                'password' => Hash::make('@ChangeMe123!'),
                'role' => 'admin',
            ]
        );
    }
}
