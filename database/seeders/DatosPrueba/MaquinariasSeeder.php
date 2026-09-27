<?php

namespace Database\Seeders\DatosPrueba;

use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Persiste las maquinarias en lotes.
 *
 * Restricción C1: `maquinarias.propiedad_id` es UNIQUE, por lo que el plan
 * garantiza como máximo una maquinaria por propiedad.
 */
class MaquinariasSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        $ahora = Carbon::now();
        $filas = [];

        foreach ($plan->usuarios as $spec) {
            $usuarioId = $contexto->usuarios[$spec['email']];

            foreach ($spec['maquinarias'] as $maquinaria) {
                $filas[] = array_merge([
                    'propiedad_id' => $contexto->propiedad($usuarioId, $maquinaria['propiedad']),
                    'tractor' => $maquinaria['tractor'],
                    'modelo_tractor' => $maquinaria['modelo_tractor'],
                    'multiple' => $maquinaria['multiple'],
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ], $maquinaria['flags']);
            }
        }

        foreach (array_chunk($filas, $this->chunk($plan)) as $lote) {
            DB::table('maquinarias')->insert($lote);
        }

        unset($filas);
    }

    private function chunk(Plan $plan): int
    {
        return (int) (config("datos-prueba.perfiles.{$plan->perfil}.chunk") ?: 500);
    }
}
