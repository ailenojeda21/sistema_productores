<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $productorRole = Role::firstOrCreate(['name' => 'productor']);

        // Crear usuario admin
        $admin = User::firstOrCreate([
            'email' => 'admin@demo.com',
        ], [
            'name' => 'Admin Demo',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole($adminRole);

        // Crear usuario productor
        $productor = User::firstOrCreate([
            'email' => 'productor@demo.com',
        ], [
            'name' => 'Productor Demo',
            'password' => Hash::make('productor123'),
        ]);
        $productor->assignRole($productorRole);
    }
}
