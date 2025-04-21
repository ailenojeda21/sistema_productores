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
                'tipo' => 'Tractor',
            ]);
            Maquinaria::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'tipo' => 'Cosechadora',
            ]);
        }
    }
}
