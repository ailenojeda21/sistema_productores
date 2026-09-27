<?php

namespace Database\Seeders\DatosPrueba;

use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Persiste los cultivos en lotes.
 *
 * `Cultivo` no tiene eventos ni mutadores, asi que se inserta directo con
 * `DB::table()` en chunks: el perfil grande son ~1500 filas y hacerlas de a
 * una por Eloquent multiplica el tiempo por 20.
 */
class CultivosSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        $ahora = Carbon::now();
        $filas = [];

        foreach ($plan->usuarios as $spec) {
            $usuarioId = $contexto->usuarios[$spec['email']];

            foreach ($spec['propiedades'] as $indice => $propiedad) {
                $propiedadId = $contexto->propiedad($usuarioId, $indice);

                foreach ($propiedad['cultivos'] as $cultivo) {
                    $filas[] = [
                        'propiedad_id' => $propiedadId,
                        'variedad' => $cultivo['variedad'],
                        'estacion' => $cultivo['estacion'],
                        'tipo' => $cultivo['tipo'],
                        'hectareas' => $cultivo['hectareas'],
                        'manejo_cultivo' => $cultivo['manejo_cultivo'],
                        'tecnologia_riego' => $cultivo['tecnologia_riego'],
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ];
                }
            }
        }

        foreach (array_chunk($filas, $this->chunk($plan)) as $lote) {
            DB::table('cultivos')->insert($lote);
        }

        unset($filas);
    }

    private function chunk(Plan $plan): int
    {
        return (int) (config("datos-prueba.perfiles.{$plan->perfil}.chunk") ?: 500);
    }
}
