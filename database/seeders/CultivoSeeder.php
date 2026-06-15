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
                'variedad' => 'Melón Amarillo',
                'estacion' => 'Verano',
                'tipo' => 'Hortícola',
                'hectareas' => 10.5,
            ]);
            Cultivo::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'variedad' => 'Malbec',
                'estacion' => 'Invierno',
                'tipo' => 'Vitícola',
                'hectareas' => 5.0,
            ]);
        }
    }
}
