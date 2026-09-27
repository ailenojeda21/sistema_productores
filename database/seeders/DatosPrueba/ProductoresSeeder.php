<?php

namespace Database\Seeders\DatosPrueba;

use App\Models\User;
use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Persiste la porción del plan correspondiente a productores.
 *
 * El email es determinista, así que `updateOrCreate` hace idempotente el
 * seeder: re-ejecutarlo no duplica filas ni choca con el índice único.
 */
class ProductoresSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        $rolProductor = Role::firstOrCreate(['name' => 'productor', 'guard_name' => 'web']);

        foreach ($plan->usuarios as $spec) {
            $usuario = $this->upsertProductor($spec, $contexto->hash($plan->password));

            $contexto->usuarios[$spec['email']] = $usuario->id;
        }

        // Asignar el rol fuera del bucle evita una consulta por usuario.
        $ids = array_values($contexto->usuarios);
        User::query()->whereIn('id', $ids)->get()->each->assignRole($rolProductor);
    }

    /**
     * @param  array<string, mixed>  $spec
     */
    private function upsertProductor(array $spec, string $password): User
    {
        // Una corrida anterior pudo dejar el productor en la papelera, con el
        // email anonimizado a `deleted-{id}@removed.invalid`. Se purga para no
        // acumular filas trash y no chocar con el indice unico.
        User::withTrashed()->where('email', $spec['email'])->get()->each->forceDelete();

        $usuario = User::query()->create([
            'name' => $spec['name'],
            'email' => $spec['email'],
            'password' => $password,
            'dni' => $spec['dni'],
            'telefono' => $spec['telefono'],
            'direccion' => $spec['direccion'],
            'avatar' => $spec['avatar'],
            'cooperativas' => $spec['cooperativas'],
        ]);

        $usuario->forceFill([
            'email_verified_at' => $spec['verificado'] ? $spec['created_at'] : null,
            'created_at' => $spec['created_at'],
            'updated_at' => $spec['created_at'],
        ])->saveQuietly();

        if ($spec['deleted_at'] !== null) {
            // El hook `User::booted()` anonimiza la fila (email ->
            // `deleted-{id}@removed.invalid`, dni -> ''). Eso destruye la clave
            // determinista y deja la fila inpurgable en la corrida siguiente,
            // asi que se restaura el email despues del delete.
            //
            // No afecta a la app: `SoftDeletes` oculta la fila de todas las
            // consultas. La anonimizacion en si se verifica con una usuaria
            // desechable en DatosPruebaIntegridadTest.
            $usuario->delete();
            $usuario->forceFill(['email' => $spec['email']])->saveQuietly();
        }

        return $usuario;
    }
}
