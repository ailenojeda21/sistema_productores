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
            CultivoSeeder::class,
        ]);

        // Dataset de pruebas: 100 productores, cada uno con mas de un cultivo,
        // mas las filas de frontera de docs/datos_prueba.md. Es idempotente:
        // purga solo el namespace que genera y vuelve a insertar.
        $this->call(DatosPruebaSeeder::class);

        // Puedes comentar o eliminar el factory de test si no lo necesitas
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
