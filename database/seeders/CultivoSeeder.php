<?php

namespace Database\Seeders;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Database\Seeder;

class CultivoSeeder extends Seeder
{
    public function run(): void
    {
        $propiedad = Propiedad::first();
        if ($propiedad) {
            Cultivo::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'variedad' => 'Híbrido',
                'estacion' => 'Verano',
                'tipo' => 'Maíz',
                'hectareas' => 10.5,
            ]);
            Cultivo::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'variedad' => 'Trigo Pan',
                'estacion' => 'Invierno',
                'tipo' => 'Trigo',
                'hectareas' => 5.0,
            ]);
        }
    }
}
