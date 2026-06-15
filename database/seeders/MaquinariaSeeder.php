<?php

namespace Database\Seeders;

use App\Models\Maquinaria;
use App\Models\Propiedad;
use Illuminate\Database\Seeder;

class MaquinariaSeeder extends Seeder
{
    public function run(): void
    {
        $propiedad = Propiedad::first();
        if ($propiedad) {
            Maquinaria::firstOrCreate(
                ['propiedad_id' => $propiedad->id],
                [
                    'propiedad_id' => $propiedad->id,
                    'tractor' => true,
                    'cosechadora' => true,
                ]
            );
        }
    }
}
