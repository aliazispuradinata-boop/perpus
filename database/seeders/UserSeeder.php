<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin RetroLib',
            'email' => 'admin@retrolib.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Perpustakaan No. 1, Jakarta',
            'is_active' => true,
        ]);

        // Create Petugas Users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@retrolib.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@retrolib.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567892',
            'address' => 'Jl. Harmoni No. 5, Jakarta',
            'is_active' => true,
        ]);

        // Create User Users
        User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@retrolib.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567893',
            'address' => 'Jl. Sudirman No. 20, Jakarta',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ratna Wulandari',
            'email' => 'ratna@retrolib.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567894',
            'address' => 'Jl. Gatot Subroto No. 15, Jakarta',
            'is_active' => true,
        ]);
    }
}
