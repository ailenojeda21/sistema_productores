<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TecnologiaRiego;
use App\Models\Propiedad;

class TecnologiaRiegoSeeder extends Seeder
{
    public function run(): void
    {
        $propiedad = Propiedad::first();
        if ($propiedad) {
            TecnologiaRiego::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'tipo' => 'Goteo',
            ]);
            TecnologiaRiego::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'tipo' => 'Aspersión',
            ]);
        }
    }
}
