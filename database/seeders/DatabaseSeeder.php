<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles y usuarios base. UserSeeder ya se protege solo: en entornos no
        // productivos crea los usuarios demo y en el resto no.
        $this->call(UserSeeder::class);

        // Los seeders de dominio crean datos de demostracion (propiedades,
        // maquinaria, cultivos y el dataset de pruebas de 100 productores):
        // solo tienen sentido en local y testing, nunca en produccion.
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn(
                'Seeders de dominio omitidos: solo se ejecutan en local y testing.'
            );

            return;
        }

        $this->call([
            PropiedadSeeder::class,
            MaquinariaSeeder::class,
            CultivoSeeder::class,
        ]);

        // Dataset de pruebas: 100 productores, cada uno con mas de un cultivo,
        // mas las filas de frontera de docs/datos_prueba.md. Es idempotente:
        // purga solo el namespace que genera y vuelve a insertar.
        $this->call(DatosPruebaSeeder::class);
    }
}
