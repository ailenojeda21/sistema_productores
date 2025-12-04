<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cultivo;
use App\Models\Propiedad;

class CultivoSeeder extends Seeder
{
    public function run(): void
    {
        $propiedad = Propiedad::first();
        if ($propiedad) {
            Cultivo::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'nombre' => 'Maíz Verano 2025',
                'estacion' => 'Verano',
                'tipo' => 'Maíz',
                'hectareas' => 10.5,
                'manejo_cultivo' => 'Convencional',
                'tecnologia_riego' => 'Surco'
            ]);
            
            Cultivo::firstOrCreate([
                'propiedad_id' => $propiedad->id,
                'nombre' => 'Trigo Invierno 2025',
                'estacion' => 'Invierno',
                'tipo' => 'Trigo',
                'hectareas' => 5.0,
                'manejo_cultivo' => 'Convencional',
                'tecnologia_riego' => 'Aspersión'
            ]);
        }
    }
}
