<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@vendora.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), 
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
