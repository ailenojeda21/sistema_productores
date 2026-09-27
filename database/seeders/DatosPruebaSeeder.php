<?php

namespace Database\Seeders;

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use App\Support\TestData\Contexto;
use App\Support\TestData\Poblador;
use Database\Seeders\DatosPrueba\ComerciosSeeder;
use Database\Seeders\DatosPrueba\CultivosSeeder;
use Database\Seeders\DatosPrueba\EscenariosSeeder;
use Database\Seeders\DatosPrueba\MaquinariasSeeder;
use Database\Seeders\DatosPrueba\ProductoresSeeder;
use Database\Seeders\DatosPrueba\PropiedadesSeeder;
use Database\Seeders\DatosPrueba\StaffUsersSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Genera el dataset de pruebas del sistema.
 *
 * Documentacion completa en docs/datos_prueba.md. Uso:
 *
 *   php artisan datos:prueba --perfil=mediano --fresh
 *   php artisan migrate:fresh --seed          # incluye este seeder
 *
 * El seeder es idempotente: primero purga el namespace que genera
 *
 * (`*@demo.test`, `*@rupal.test`, `*@datos-prueba.test`) y recien despues
 * inserta. Los datos reales cargados a mano no se tocan.
 */
class DatosPruebaSeeder extends Seeder
{
    /** Dominios que identifican filas creadas por este seeder. */
    private const DOMINIOS = ['@demo.test', '@rupal.test', '@datos-prueba.test'];

    public function run(?string $perfil = null, bool $purgar = true, bool $escenarios = true): void
    {
        $plan = (new Poblador)->plan($perfil);

        if ($purgar) {
            $this->purgar();
        }

        $contexto = new Contexto;

        DB::transaction(function () use ($plan, $contexto, $escenarios) {
            $this->callWith(StaffUsersSeeder::class, compact('plan', 'contexto'));
            $this->callWith(ProductoresSeeder::class, compact('plan', 'contexto'));
            $this->callWith(PropiedadesSeeder::class, compact('plan', 'contexto'));
            $this->callWith(CultivosSeeder::class, compact('plan', 'contexto'));
            $this->callWith(MaquinariasSeeder::class, compact('plan', 'contexto'));
            $this->callWith(ComerciosSeeder::class, compact('plan', 'contexto'));

            if ($escenarios) {
                $this->callWith(EscenariosSeeder::class, compact('plan', 'contexto'));
            }
        });
    }

    /**
     * Borra solo lo que genera este seeder.
     *
     * El orden respeta las claves foraneas porque SQLite no aplica
     * ON DELETE CASCADE salvo que este activo el PRAGMA foreign_keys.
     */
    private function purgar(): void
    {
        DB::transaction(function () {
            $ids = $this->idsUsuarios();

            Cultivo::query()->whereIn('propiedad_id', $this->idsPropiedades($ids))->delete();
            Maquinaria::query()->whereIn('propiedad_id', $this->idsPropiedades($ids))->delete();
            Comercio::query()->whereIn('usuario_id', $ids)->delete();
            Propiedad::query()->whereIn('usuario_id', $ids)->delete();

            User::withTrashed()->whereIn('id', $ids)->get()->each->forceDelete();

            StaffUser::withTrashed()->where('email', 'like', '%@rupal.test')->get()
                ->each->forceDelete();
        });
    }

    /**
     * @return array<int, int>
     */
    private function idsUsuarios(): array
    {
        return User::withTrashed()
            ->where(function ($query) {
                foreach (self::DOMINIOS as $dominio) {
                    $query->orWhere('email', 'like', '%'.$dominio);
                }
            })
            ->pluck('id')
            ->all();
    }

    /**
     * @param  array<int, int>  $idsUsuarios
     * @return array<int, int>
     */
    private function idsPropiedades(array $idsUsuarios): array
    {
        return Propiedad::query()->whereIn('usuario_id', $idsUsuarios)->pluck('id')->all();
    }
}
