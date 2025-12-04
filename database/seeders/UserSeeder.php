<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin Demo',
                'password' => Hash::make('admin123'),
                'es_propietario' => true,
                'dni' => '12345678'
            ]
        );

        // Crear usuario productor
        User::firstOrCreate(
            ['email' => 'productor@demo.com'],
            [
                'name' => 'Productor Demo',
                'password' => Hash::make('productor123'),
                'es_propietario' => true,
                'dni' => '87654321'
            ]
        );
    }
}
