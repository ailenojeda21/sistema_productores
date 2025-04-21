<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Propiedad;
use App\Models\User;

class PropiedadSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::where('email', 'productor@demo.com')->first();
        if ($usuario) {
            Propiedad::firstOrCreate([
                'usuario_id' => $usuario->id,
                'ubicacion' => 'Campo Norte',
                'superficie' => 15.5,
                'malla_antigranizo' => true,
                'es_propietario' => true,
                'derecho_riego' => true,
            ]);
            Propiedad::firstOrCreate([
                'usuario_id' => $usuario->id,
                'ubicacion' => 'Campo Sur',
                'superficie' => 8.2,
                'malla_antigranizo' => false,
                'es_propietario' => false,
                'derecho_riego' => false,
            ]);
        }
    }
}
