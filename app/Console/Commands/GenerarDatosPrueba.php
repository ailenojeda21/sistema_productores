<?php

namespace App\Console\Commands;

use App\Support\TestData\Plan;
use App\Support\TestData\Poblador;
use Database\Seeders\DatosPruebaSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Genera el dataset de pruebas del sistema.
 *
 * Ver docs/datos_prueba.md para el detalle de los criterios C1..C10.
 */
class GenerarDatosPrueba extends Command
{
    protected $signature = 'datos:prueba
        {--perfil= : perfil a generar (pequeno, mediano, grande)}
        {--semilla= : semilla del generador; misma semilla = mismo dataset}
        {--referencia= : instante de referencia de las fechas (Y-m-d H:i:s)}
        {--password= : password de las cuentas generadas}
        {--fresh : purga TODAS las tablas de dominio antes de insertar, no solo el namespace generado}
        {--sin-escenarios : omite las filas golden de EscenariosSeeder}
        {--dry-run : imprime el plan sin tocar la base}';

    protected $description = 'Genera el dataset de pruebas: productores, propiedades, cultivos, maquinarias, comercios y staff';

    public function handle(): int
    {
        $this->aplicarOpciones();

        try {
            $plan = (new Poblador)->plan($this->opcion('perfil'));
        } catch (InvalidArgumentException $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        if ($this->option('dry-run')) {
            $this->mostrarPlan($plan);

            return self::SUCCESS;
        }

        if ($this->option('fresh')) {
            $this->components->warn('--fresh: se borran TODAS las filas de las tablas de dominio, incluidos los seeders demo.');
            $this->purgarTodo();
        }

        $this->components->info("Generando dataset de pruebas (perfil: {$plan->perfil}, semilla: {$plan->semilla})");

        $inicio = microtime(true);

        // `db:seed` no reenvía parámetros arbitrarios al seeder, así que se
        // resuelve e invoca a mano para inyectar perfil y banderas.
        $seeder = app(DatosPruebaSeeder::class);
        $seeder->setContainer(app());
        $seeder->setCommand($this);
        $seeder->__invoke([
            'perfil' => $plan->perfil,
            'purgar' => true,
            'escenarios' => ! $this->option('sin-escenarios'),
        ]);

        $this->components->info(sprintf('Dataset listo en %.1f s.', microtime(true) - $inicio));
        $this->mostrarResumen($plan);

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------- opciones */

    private function aplicarOpciones(): void
    {
        if ($perfil = $this->opcion('perfil')) {
            Config::set('datos-prueba.perfil', $perfil);
        }

        if ($semilla = $this->opcion('semilla')) {
            Config::set('datos-prueba.semilla', (int) $semilla);
        }

        if ($password = $this->opcion('password')) {
            Config::set('datos-prueba.password', $password);
        }

        if ($referencia = $this->opcion('referencia')) {
            Config::set('datos-prueba.referencia', Carbon::parse($referencia));
        }
    }

    private function opcion(string $nombre): ?string
    {
        $valor = $this->option($nombre);

        return is_string($valor) && $valor !== '' ? $valor : null;
    }

    /* ---------------------------------------------------------------- purge */

    /**
     * Borra las tablas de dominio respetando el orden de las claves foraneas
     * (portable entre MySQL y SQLite, a diferencia de FOREIGN_KEY_CHECKS).
     */
    private function purgarTodo(): void
    {
        DB::transaction(function () {
            foreach (['cultivos', 'maquinarias', 'comercios', 'propiedades', 'users', 'staff_users'] as $tabla) {
                DB::table($tabla)->delete();
            }
        });
    }

    /* --------------------------------------------------------------- output */

    private function mostrarPlan(Plan $plan): void
    {
        $this->table(['Entidad', 'Total'], $this->filas($plan));
        $this->components->twoColumnDetail('Perfil', $plan->perfil);
        $this->components->twoColumnDetail('Semilla', (string) $plan->semilla);
        $this->components->twoColumnDetail('Referencia', (string) config('datos-prueba.referencia', 'ahora'));
    }

    private function mostrarResumen(Plan $plan): void
    {
        $usuarios = DB::table('users')->where('email', 'like', '%@demo.test')->count();

        $this->table(['Entidad', 'Plan', 'En base (namespace generado)'], [
            ['Productores', count($plan->usuarios), $usuarios],
            ['Propiedades', $plan->totalPropiedades(), $this->contarDeUsuarios($usuarios)],
            ['Cultivos', $plan->totalCultivos(), $this->contarCultivos($usuarios)],
            ['Maquinarias', $plan->totalMaquinarias(), $this->contarMaquinarias($usuarios)],
            ['Comercios', $plan->totalComercios(), $this->contarComercios($usuarios)],
            ['Staff', count($plan->staff), DB::table('staff_users')->where('email', 'like', '%@rupal.test')->count()],
        ]);

        $this->newLine();
        $this->components->twoColumnDetail('Password', $plan->password);
        $this->components->twoColumnDetail('Productor', 'productor000@demo.test');
        $this->components->twoColumnDetail('Escenarios', 'escenarios@datos-prueba.test');
    }

    /**
     * @return array<int, array<int, string|int>>
     */
    private function filas(Plan $plan): array
    {
        return [
            ['Productores', count($plan->usuarios)],
            ['Propiedades', $plan->totalPropiedades()],
            ['Cultivos', $plan->totalCultivos()],
            ['Maquinarias', $plan->totalMaquinarias()],
            ['Comercios', $plan->totalComercios()],
            ['Staff', count($plan->staff)],
        ];
    }

    private function contarDeUsuarios(int $usuarios): int
    {
        return $usuarios === 0 ? 0 : DB::table('propiedades')
            ->whereIn('usuario_id', $this->idsUsuarios())
            ->count();
    }

    private function contarCultivos(int $usuarios): int
    {
        return $usuarios === 0 ? 0 : DB::table('cultivos')
            ->whereIn('propiedad_id', $this->idsPropiedades())
            ->count();
    }

    private function contarMaquinarias(int $usuarios): int
    {
        return $usuarios === 0 ? 0 : DB::table('maquinarias')
            ->whereIn('propiedad_id', $this->idsPropiedades())
            ->count();
    }

    private function contarComercios(int $usuarios): int
    {
        return $usuarios === 0 ? 0 : DB::table('comercios')
            ->whereIn('usuario_id', $this->idsUsuarios())
            ->count();
    }

    /**
     * @return array<int, int>
     */
    private function idsUsuarios(): array
    {
        return DB::table('users')->where('email', 'like', '%@demo.test')->pluck('id')->all();
    }

    /**
     * @return array<int, int>
     */
    private function idsPropiedades(): array
    {
        return DB::table('propiedades')
            ->whereIn('usuario_id', $this->idsUsuarios())
            ->pluck('id')
            ->all();
    }
}
