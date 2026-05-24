<?php

namespace Database\Seeders;

use App\Models\Propiedad;
use App\Models\TecnologiaRiego;
use Illuminate\Database\Seeder;

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
