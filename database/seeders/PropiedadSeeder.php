<?php

namespace Database\Seeders;

use App\Models\Propiedad;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropiedadSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::where('email', 'productor@demo.com')->first();
        if ($usuario) {
            $data = [
                [
                    'usuario_id' => $usuario->id,
                    'calle' => 'Campo Norte',
                    'hectareas' => 15.5,
                    'malla' => true,
                    'tipo_tenencia' => 'propietario',
                    'derecho_riego' => true,
                ],
                [
                    'usuario_id' => $usuario->id,
                    'calle' => 'Campo Sur',
                    'hectareas' => 8.2,
                    'malla' => false,
                    'tipo_tenencia' => 'arrendatario',
                    'derecho_riego' => false,
                ],
            ];

            foreach ($data as $attrs) {
                $prop = Propiedad::firstOrNew(['calle' => $attrs['calle'], 'usuario_id' => $attrs['usuario_id']]);
                if (!$prop->exists) {
                    $prop->forceFill($attrs)->save();
                }
            }
        }
    }
}
