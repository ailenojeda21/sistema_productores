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
                'direccion' => 'Campo Norte, Ruta 40',
                'lat' => -34.6037,
                'lng' => -58.3816,
                'hectareas' => 15.5,
                'es_propietario' => true,
                'malla' => true,
                'derecho_riego' => true,
                'tipo_derecho_riego' => 'Permanente',
                'rut' => true,
                'rut_valor' => 5000.00,
                'hectareas_malla' => 10.0,
                'cierre_perimetral' => true
            ]);

            Propiedad::firstOrCreate([
                'usuario_id' => $usuario->id,
                'direccion' => 'Campo Sur, Ruta 7',
                'lat' => -34.6040,
                'lng' => -58.3820,
                'hectareas' => 8.2,
                'es_propietario' => false,
                'malla' => false,
                'derecho_riego' => false,
                'tipo_derecho_riego' => null,
                'rut' => false,
                'rut_valor' => null,
                'hectareas_malla' => null,
                'cierre_perimetral' => false
            ]);
        }
    }
}
