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
                'calle' => 'Campo Norte',
                'hectareas' => 15.5,
                'malla' => true,
                'tipo_tenencia' => 'Propio',
                'derecho_riego' => true,
            ]);
            Propiedad::firstOrCreate([
                'usuario_id' => $usuario->id,
                'calle' => 'Campo Sur',
                'hectareas' => 8.2,
                'malla' => false,
                'tipo_tenencia' => 'Arrendado',
                'derecho_riego' => false,
            ]);
        }
    }
}
