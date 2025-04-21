<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Implemento;
use App\Models\Maquinaria;

class ImplementoSeeder extends Seeder
{
    public function run(): void
    {
        $maquinaria = Maquinaria::first();
        if ($maquinaria) {
            Implemento::firstOrCreate([
                'maquinaria_id' => $maquinaria->id,
                'nombre' => 'Arado',
            ]);
            Implemento::firstOrCreate([
                'maquinaria_id' => $maquinaria->id,
                'nombre' => 'Rastra',
            ]);
        }
    }
}
