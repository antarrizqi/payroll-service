<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // ganti dengan password aman
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Karyawan Biasa',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);
    }
}