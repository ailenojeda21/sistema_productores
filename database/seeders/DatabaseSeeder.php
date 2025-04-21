<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Llamar a los seeders personalizados
        $this->call([
            UserSeeder::class,
            PropiedadSeeder::class,
            MaquinariaSeeder::class,
            ImplementoSeeder::class,
            CultivoSeeder::class,
            TecnologiaRiegoSeeder::class,
        ]);
        // Puedes comentar o eliminar el factory de test si no lo necesitas
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
