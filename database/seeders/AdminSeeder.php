<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // We need this to encrypt your password securely!

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@system.com',
            'password' => Hash::make('admin123'), // Encrypts the password
            'role' => 'admin'
        ]);
    }
}