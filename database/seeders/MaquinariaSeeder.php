<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maquinaria;
use App\Models\Propiedad;

class MaquinariaSeeder extends Seeder
{
    public function run(): void
    {
        $propiedad = Propiedad::first();
        if ($propiedad) {
            Maquinaria::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'tractor' => true,
                'modelo_tractor' => 2020,
                'arado' => true,
                'rastra' => true,
                'niveleta_comun' => true,
                'niveleta_laser' => false,
                'cincel_cultivadora' => true,
                'desmalezadora' => true,
                'pulverizadora_tractor' => true,
                'mochila_pulverizadora' => true,
                'cosechadora' => true,
                'enfardadora' => false,
                'retroexcavadora' => false,
                'carro_carreton' => true
            ]);
        }
    }
}
