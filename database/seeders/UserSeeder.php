<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Kasir 1
        User::create([
            'name' => 'Kasir Satu',
            'username' => 'kasir1',
            'email' => 'kasir1@example.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
        ]);

        // Kasir 2
        User::create([
            'name' => 'Kasir Dua',
            'username' => 'kasir2',
            'email' => 'kasir2@example.com',
            'role' => 'kasir',
            'password' => Hash::make('password'),
        ]);
    }
}
