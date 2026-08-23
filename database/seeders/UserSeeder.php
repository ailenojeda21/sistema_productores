<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $productorRole = Role::firstOrCreate(['name' => 'productor']);

        // Usuarios demo con credenciales triviales: solo entornos no productivos.
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $admin = User::firstOrCreate([
            'email' => 'admin@demo.com',
        ], [
            'name' => 'Admin Demo',
            'password' => Hash::make('admin123'),
            'dni' => '20123456',
            'telefono' => '2614000001',
            'direccion' => 'Av. San Martín 1234, Mendoza',
        ]);
        $admin->markEmailAsVerified();
        $admin->assignRole($adminRole);

        $productor = User::firstOrCreate([
            'email' => 'productor@demo.com',
        ], [
            'name' => 'Productor Demo',
            'password' => Hash::make('productor123'),
            'dni' => '30654321',
            'telefono' => '2614000002',
            'direccion' => 'Ruta Provincial 50 km 12, Luján de Cuyo',
        ]);
        $productor->markEmailAsVerified();
        $productor->assignRole($productorRole);
    }
}
