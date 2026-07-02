<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Boss Kopi',
            'email' => 'admin@bosskopi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_hp' => '0895333301223',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@bosskopi.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'no_hp' => '081234567891',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'pembeli@bosskopi.com',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
            'no_hp' => '081234567892',
            'is_active' => true,
            'poin_loyalitas' => 150,
        ]);

        User::create([
            'name' => 'Driver Andalan',
            'email' => 'driver@bosskopi.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'no_hp' => '081234567893',
            'is_active' => true,
        ]);
    }
}
