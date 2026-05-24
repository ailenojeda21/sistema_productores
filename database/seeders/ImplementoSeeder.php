<?php

namespace Database\Seeders;

use App\Models\Implemento;
use App\Models\Maquinaria;
use Illuminate\Database\Seeder;

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
