<?php

namespace Database\Seeders\DatosPrueba;

use App\Models\Propiedad;
use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;

/**
 * Persiste las propiedades de cada productor.
 *
 * `usuario_id` no esta en el `$fillable` de `Propiedad`, asi que se usa la
 * relacion hasMany en lugar de `create()` con mass assignment.
 */
class PropiedadesSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        foreach ($plan->usuarios as $spec) {
            $usuarioId = $contexto->usuarios[$spec['email']];
            $contexto->propiedades[$usuarioId] = [];

            foreach ($spec['propiedades'] as $indice => $propiedad) {
                $model = $this->crearPropiedad($usuarioId, $spec['propiedades'], $indice);

                $contexto->propiedades[$usuarioId][$indice] = $model->id;
            }
        }
    }

    /**
     * `usuario_id` no esta en el `$fillable` de `Propiedad`, asi que se
     * escribe con `forceFill` sobre una instancia nueva.
     *
     * @param  array<int, array<string, mixed>>  $propiedades
     */
    private function crearPropiedad(int $usuarioId, array $propiedades, int $indice): Propiedad
    {
        $rut = $propiedades[$indice]['rut'];
        $propiedad = new Propiedad;

        $propiedad->forceFill([
            'usuario_id' => $usuarioId,
            'calle' => $rut['calle'],
            'numeracion' => $rut['numeracion'],
            'distrito' => $rut['distrito'],
            'hectareas' => $rut['hectareas'],
            'derecho_riego' => $rut['derecho_riego'],
            'tipo_derecho_riego' => $rut['tipo_derecho_riego'],
            'rut' => $rut['rut'],
            'rut_valor' => $rut['rut_valor'],
            'malla' => $rut['malla'],
            'hectareas_malla' => $rut['hectareas_malla'],
            'cierre_perimetral' => $rut['cierre_perimetral'],
            'tipo_tenencia' => $rut['tipo_tenencia'],
            'especificar_tenencia' => $rut['especificar_tenencia'],
            'lat' => $rut['lat'],
            'lng' => $rut['lng'],
        ])->save();

        return $propiedad;
    }
}
