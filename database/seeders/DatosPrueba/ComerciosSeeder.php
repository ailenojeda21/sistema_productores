<?php

namespace Database\Seeders\DatosPrueba;

use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Persiste la comercializacion de cada productor.
 *
 * Restricción C1: `comercios.usuario_id` es UNIQUE, asi que hay como maximo
 * un registro de comercio por productor.
 *
 * `mercados` y `cooperativas` son columnas JSON con collation `utf8mb4_bin`:
 * guardan las ETIQUETAS, no las claves, porque eso es lo que envia el
 * formulario. Se insertan con `DB::table()` para poder escribir `null` y `[]`
 * sin que el cast `array` de Eloquent los normalice.
 */
class ComerciosSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        $ahora = Carbon::now();
        $filas = [];

        foreach ($plan->usuarios as $spec) {
            if ($spec['comercio'] === null) {
                continue;
            }

            $comercio = $spec['comercio'];

            $filas[] = [
                'usuario_id' => $contexto->usuarios[$spec['email']],
                'infraestructura_empaque' => $comercio['infraestructura_empaque'],
                'vende_en_finca' => $comercio['vende_en_finca'],
                'mercados' => $this->json($comercio['mercados']),
                'cooperativas' => $this->json($comercio['cooperativas']),
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];
        }

        if ($filas !== []) {
            DB::table('comercios')->insert($filas);
        }
    }

    /**
     * @param  array<int, string>|null  $valor
     */
    private function json(?array $valor): ?string
    {
        return $valor === null ? null : json_encode($valor, JSON_UNESCAPED_UNICODE);
    }
}
