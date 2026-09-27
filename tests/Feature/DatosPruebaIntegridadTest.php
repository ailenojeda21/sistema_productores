<?php

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\User;
use App\Support\TestData\Plan;
use App\Support\TestData\Poblador;
use Database\Seeders\DatosPruebaSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Criterios de integridad del dataset (C1..C10 de docs/datos_prueba.md).
 *
 * `sembrar()` es idempotente, asi que se puede llamar en cada test sin que
 * las pruebas se contaminen entre si (RefreshDatabase aisla cada una).
 */
function sembrar(string $perfil = 'mediano'): void
{
    (new DatosPruebaSeeder)->run($perfil, purgar: true, escenarios: false);
}

/**
 * @return array<int, string>
 */
function correosGenerados(): array
{
    return DB::table('users')->where('email', 'like', '%@demo.test')->pluck('email')->all();
}

/**
 * @return array<int, string>
 */
function todasLasVariedades(): array
{
    $todas = [];

    foreach (array_keys(Cultivo::TIPOS) as $tipo) {
        $todas = array_merge($todas, array_keys(Cultivo::getVariedadesForTipo($tipo)));
    }

    return $todas;
}

function huellaDelPlan(Plan $plan): string
{
    return json_encode([$plan->usuarios, $plan->staff], JSON_THROW_ON_ERROR);
}

// =====================================================================
// C1 - CARDINALIDAD
// =====================================================================

test('cada productor con propiedades tiene mas de un cultivo', function () {
    sembrar();

    $conDosOMenos = DB::table('cultivos')
        ->join('propiedades', 'cultivos.propiedad_id', '=', 'propiedades.id')
        ->whereIn('propiedades.usuario_id', function ($q) {
            $q->select('id')->from('users')->where('email', 'like', '%@demo.test');
        })
        ->groupBy('propiedades.usuario_id')
        ->havingRaw('COUNT(cultivos.id) < 2')
        ->pluck('propiedades.usuario_id');

    expect($conDosOMenos)->toBeEmpty();
});

test('la cohorte sin propiedades existe y no tiene ninguna propiedad', function () {
    sembrar();

    $sinPropiedades = DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->whereNotExists(fn ($q) => $q->select(DB::raw(1))
            ->from('propiedades')
            ->whereColumn('propiedades.usuario_id', 'users.id'))
        ->count();

    expect($sinPropiedades)->toBeGreaterThan(0);
});

test('la suma de hectareas de los cultivos nunca supera las de la propiedad', function () {
    sembrar();

    $rupturas = DB::table('propiedades as p')
        ->leftJoin('cultivos as c', 'c.propiedad_id', '=', 'p.id')
        ->groupBy('p.id', 'p.hectareas')
        ->havingRaw('COALESCE(SUM(c.hectareas), 0) > p.hectareas')
        ->pluck('p.id');

    expect($rupturas)->toBeEmpty();
});

test('la malla nunca supera la superficie de la propiedad', function () {
    sembrar();

    expect(DB::table('propiedades')
        ->whereNotNull('hectareas_malla')
        ->whereColumn('hectareas_malla', '>', 'hectareas')
        ->count())->toBe(0);
});

test('hectareas_malla nunca es negativo', function () {
    sembrar();

    expect(DB::table('propiedades')->where('hectareas_malla', '<', 0)->count())->toBe(0);
});

test('la tenencia "otros" siempre trae especificacion', function () {
    sembrar();

    expect(DB::table('propiedades')
        ->where('tipo_tenencia', 'otros')
        ->where(fn ($q) => $q->whereNull('especificar_tenencia')->orWhere('especificar_tenencia', ''))
        ->count())->toBe(0);
});

test('un tractor exige modelo y sin tractor el modelo es null', function () {
    sembrar();

    expect(DB::table('maquinarias')->where('tractor', true)->whereNull('modelo_tractor')->count())->toBe(0)
        ->and(DB::table('maquinarias')->where('tractor', false)->whereNotNull('modelo_tractor')->count())->toBe(0);
});

test('el anio del tractor respeta los limites de StoreMaquinariaRequest', function () {
    sembrar();

    $fuera = DB::table('maquinarias')
        ->whereNotNull('modelo_tractor')
        ->where(fn ($q) => $q->where('modelo_tractor', '<', 1900)
            ->orWhere('modelo_tractor', '>', (int) date('Y')))
        ->count();

    expect($fuera)->toBe(0);
});

// =====================================================================
// C2 - UNICIDAD
// =====================================================================

test('no hay mas de una maquinaria por propiedad', function () {
    sembrar();

    expect(DB::table('maquinarias')->groupBy('propiedad_id')->havingRaw('COUNT(*) > 1')->count())->toBe(0);
});

test('no hay mas de un registro de comercializacion por productor', function () {
    sembrar();

    expect(DB::table('comercios')->groupBy('usuario_id')->havingRaw('COUNT(*) > 1')->count())->toBe(0);
});

test('los DNI generados no se repiten', function () {
    sembrar();

    $dnis = DB::table('users')->where('email', 'like', '%@demo.test')->where('dni', '!=', '')->pluck('dni');

    expect($dnis->count())->toBe($dnis->unique()->count());
});

test('los correos generados no se repiten', function () {
    sembrar();

    $emails = DB::table('users')->where('email', 'like', '%@demo.test')->pluck('email');

    expect($emails->count())->toBe($emails->unique()->count());
});

// =====================================================================
// C3 - DETERMINISMO E IDEMPOTENCIA
// =====================================================================

test('misma semilla y misma referencia producen el mismo plan', function () {
    $referencia = '2026-09-26 12:00:00';

    $a = (new Poblador(20260905, $referencia))->plan('mediano');
    $b = (new Poblador(20260905, $referencia))->plan('mediano');
    $c = (new Poblador(777, $referencia))->plan('mediano');

    expect(huellaDelPlan($a))->toBe(huellaDelPlan($b))
        ->and(huellaDelPlan($a))->not->toBe(huellaDelPlan($c));
});

test('sembrar dos veces no deja filas duplicadas', function () {
    sembrar();
    sembrar();

    expect(DB::table('users')->where('email', 'like', '%@demo.test')->count())->toBe(100)
        ->and(DB::table('propiedades')->whereIn('usuario_id', function ($q) {
            $q->select('id')->from('users')->where('email', 'like', '%@demo.test');
        })->count())->toBe(210);
});

test('la papelera conserva la clave determinista del email', function () {
    sembrar();

    expect(DB::table('users')->whereNotNull('deleted_at')->count())->toBeGreaterThan(0)
        ->and(DB::table('users')
            ->whereNotNull('deleted_at')
            ->where('email', 'like', '%@demo.test')
            ->count())->toBeGreaterThan(0);
});

test('el soft delete de User anonimiza la fila', function () {
    $usuario = User::factory()->create();

    $usuario->delete();

    expect($usuario->fresh()->name)->toBe('Usuario eliminado')
        ->and($usuario->fresh()->dni)->toBe('')
        ->and($usuario->fresh()->email)->toContain('@removed.invalid');
});

// =====================================================================
// C4 - COBERTURA DE WHITELISTS
// =====================================================================

test('estan cubiertos todos los tipos de cultivo del whitelist', function () {
    sembrar();

    $esperados = array_keys(Cultivo::TIPOS);
    $presentes = DB::table('cultivos')->distinct()->pluck('tipo')->sort()->values()->all();
    sort($esperados);

    expect($presentes)->toBe($esperados);
});

test('estan cubiertas todas las tecnologias de riego del whitelist', function () {
    sembrar();

    $esperados = array_keys(Cultivo::TECNOLOGIA_RIEGO);
    $presentes = DB::table('cultivos')->whereNotNull('tecnologia_riego')->distinct()->pluck('tecnologia_riego')->sort()->values()->all();
    sort($esperados);

    expect($presentes)->toBe($esperados);
});

test('toda variedad pertenece al whitelist de su tipo', function () {
    sembrar();

    expect(DB::table('cultivos')->whereNotIn('variedad', todasLasVariedades())->count())->toBe(0);
});

test('el dataset cubre las combinaciones tipo/variedad del perfil mediano', function () {
    sembrar();

    // `count(['a','b'])` no es portable a SQLite, asi que se cuenta en PHP.
    $combinaciones = DB::table('cultivos')
        ->select('tipo', 'variedad')
        ->distinct()
        ->get()
        ->count();

    expect($combinaciones)->toBeGreaterThanOrEqual(60);
});

// =====================================================================
// C6 - COBERTURA TEMPORAL
// =====================================================================

test('la cohorte antigua cae fuera de la ventana de 90 dias', function () {
    sembrar();

    expect(DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->where('created_at', '<', now()->subDays(90))
        ->count())->toBeGreaterThan(0);
});

test('hay registros dentro de la ventana reciente de 90 dias', function () {
    sembrar();

    expect(DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->where('created_at', '>=', now()->subDays(90))
        ->count())->toBeGreaterThan(0);
});

// =====================================================================
// C7 - SIMILITUD DE NOMBRES
// =====================================================================

test('hay productores con nombre exactamente duplicado', function () {
    sembrar();

    $duplicados = DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->select('name', DB::raw('COUNT(*) as total'))
        ->groupBy('name')
        ->havingRaw('COUNT(*) > 1')
        ->pluck('total');

    expect($duplicados)->not->toBeEmpty();
});

test('hay pares de nombres que solo difieren en el acento', function () {
    sembrar();

    $conAcento = DB::table('users')->where('name', 'like', '%é%')->count();
    $conTilde = DB::table('users')->where('name', 'like', '%Perez%')->count();

    expect($conAcento)->toBeGreaterThan(0)->and($conTilde)->toBeGreaterThan(0);
});

// =====================================================================
// C8 - PAGINACION (paginate(10))
// =====================================================================

test('algún distrito agrupa mas de 10 productores', function () {
    sembrar();

    $mayor = DB::table('propiedades')
        ->whereIn('usuario_id', function ($q) {
            $q->select('id')->from('users')->where('email', 'like', '%@demo.test');
        })
        ->select('distrito', DB::raw('COUNT(DISTINCT usuario_id) as productores'))
        ->groupBy('distrito')
        ->orderByDesc('productores')
        ->first();

    expect($mayor->productores)->toBeGreaterThan(10);
});

test('el prefijo de DNI compartido supera las 10 filas', function () {
    sembrar();

    expect(DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->where('dni', 'like', '3012%')
        ->count())->toBeGreaterThan(10);
});

test('el apellido denso supera las 10 filas', function () {
    sembrar();

    expect(DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->where('name', 'like', 'Benegas%')
        ->count())->toBeGreaterThan(10);
});

// =====================================================================
// C9 - SENTINELAS
// =====================================================================

test('el password del dataset verifica con Hash::check', function () {
    sembrar();

    $hash = DB::table('users')->where('email', 'productor000@demo.test')->value('password');

    expect(Hash::check('DatosPrueba2026!', $hash))->toBeTrue();
});

test('hay productores sin perfil completo para probar el certificado bloqueado', function () {
    sembrar();

    $incompletos = DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->where(fn ($q) => $q->where('dni', '')->orWhere('telefono', '')->orWhere('direccion', ''))
        ->count();

    expect($incompletos)->toBeGreaterThan(0);
});

test('hay productores sin verificar el email', function () {
    sembrar();

    expect(DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->whereNull('email_verified_at')
        ->count())->toBeGreaterThan(0);
});

// =====================================================================
// C10 - EXPORTABILIDAD
// =====================================================================

test('mercados y cooperativas son JSON valido o null', function () {
    sembrar();

    expect(DB::table('comercios')
        ->whereNotNull('mercados')->whereRaw("mercados NOT LIKE '[%'")
        ->count())->toBe(0)
        ->and(DB::table('comercios')
            ->whereNotNull('cooperativas')->whereRaw("cooperativas NOT LIKE '[%'")
            ->count())->toBe(0);
});

test('toda comercializacion tiene al menos una via de venta', function () {
    sembrar();

    expect(DB::table('comercios')
        ->where('vende_en_finca', false)
        ->where(fn ($q) => $q->where('mercados', '[]')->orWhere('mercados', 'null'))
        ->where(fn ($q) => $q->where('cooperativas', '[]')->orWhere('cooperativas', 'null'))
        ->count())->toBe(0);
});

test('el dataset incluye claves de mercado fuera del vocabulario', function () {
    sembrar();

    $vocabulario = array_values(Comercio::MERCADOS);
    $huerfanas = DB::table('comercios')->whereNotNull('mercados')->get()
        ->flatMap(fn ($c) => json_decode($c->mercados, true) ?? [])
        ->reject(fn ($m) => in_array($m, $vocabulario, true))
        ->unique();

    expect($huerfanas)->not->toBeEmpty();
});

// =====================================================================
// STAFF
// =====================================================================

test('la matriz de permisos tiene administradores y auditores', function () {
    sembrar();

    expect(DB::table('staff_users')->where('role', 'admin')->count())->toBeGreaterThan(0)
        ->and(DB::table('staff_users')->where('role', 'auditor')->count())->toBeGreaterThan(0);
});

test('el dominio compartido de staff supera las 10 filas para el filtro email', function () {
    sembrar();

    expect(DB::table('staff_users')->where('email', 'like', '%@rupal.test')->count())->toBeGreaterThan(10);
});

test('existen cuentas staff inactivas y al menos una dada de baja', function () {
    sembrar();

    expect(DB::table('staff_users')->where('active', false)->count())->toBeGreaterThan(0)
        ->and(DB::table('staff_users')->whereNotNull('deleted_at')->count())->toBeGreaterThan(0);
});

// =====================================================================
// PERFILES
// =====================================================================

test('el perfil pequeno genera exactamente los totales anunciados', function () {
    sembrar('pequeno');

    expect(DB::table('users')->where('email', 'like', '%@demo.test')->count())->toBe(30)
        ->and(DB::table('propiedades')->count())->toBe(55)
        ->and(DB::table('cultivos')->count())->toBe(81);
});

test('un perfil desconocido falla con un mensaje util', function () {
    expect(fn () => (new Poblador)->plan('gigante'))
        ->toThrow(InvalidArgumentException::class, "Perfil de datos de prueba desconocido: 'gigante'");
});
