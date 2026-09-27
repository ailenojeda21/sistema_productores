<?php

namespace Database\Seeders\DatosPrueba;

use App\Models\StaffUser;
use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;

/**
 * Persiste las cuentas staff.
 *
 * Los emails comparten el dominio `@rupal.test`, asi que el filtro `email` de
 * `StaffUserController` tiene un token con >10 coincidencias (2 paginas).
 *
 * No hay `StaffUserSeeder` en el proyecto: `migrate --seed` nunca poblaba esta
 * tabla, que es justamente la que se usa para probar la matriz de permisos
 * admin/auditor de `AuthServiceProvider`.
 */
class StaffUsersSeeder extends Seeder
{
    public function run(Plan $plan, Contexto $contexto): void
    {
        foreach ($plan->staff as $spec) {
            StaffUser::withTrashed()->where('email', $spec['email'])->get()->each->forceDelete();

            $staff = StaffUser::query()->create([
                'name' => $spec['name'],
                'email' => $spec['email'],
                'password' => $contexto->hash($plan->password),
                'role' => $spec['role'],
                'active' => $spec['active'],
            ]);

            $staff->forceFill([
                'last_login_at' => $spec['last_login_at'],
                'last_access_at' => $spec['last_access_at'],
            ])->saveQuietly();

            if ($spec['deleted_at'] !== null) {
                $staff->delete();
            }

            $contexto->staff[$spec['email']] = $staff->id;
        }
    }
}
