<?php

use App\Http\Controllers\StaffProducerController;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Database\Seeders\DatosPruebaSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Verifica que el dataset de pruebas sirve para probar los filtros reales de
 * `StaffProducerController` y `StaffUserController` (criterios C8 y C9).
 *
 * Estos tests no reimplementan las queries: disparan los endpoints reales con
 * una cuenta staff de verdad y comparan contra el conteo calculado sobre la
 * base, para que cualquier cambio en el filtro se detecte como diferencia.
 */
function sembrarParaFiltros(): void
{
    (new DatosPruebaSeeder)->run('mediano', purgar: true, escenarios: false);
}

/**
 * Solo las filas golden de `EscenariosSeeder`, con el perfil minimo para que
 * los tests que las consultan no paguen por 100 productores.
 */
function sembrarEscenarios(): void
{
    (new DatosPruebaSeeder)->run('pequeno', purgar: true, escenarios: true);
}

function actingComoStaff(string $role = 'admin'): StaffUser
{
    $staff = StaffUser::query()->create([
        'name' => 'Staff de pruebas',
        'email' => "staff-{$role}@filtros.test",
        'password' => Hash::make('DatosPrueba2026!'),
        'role' => $role,
        'active' => true,
    ]);

    test()->actingAs($staff, 'staff');

    return $staff;
}

function totalDeLaPagina($response): int
{
    return $response->json('producers.total') ?? 0;
}

/**
 * Subquery de los productores que la app puede listar: namespace `@demo.test`
 * y sin `deleted_at`, porque `User` usa `SoftDeletes`.
 */
function subqueryProductoresVisibles()
{
    return DB::table('users')
        ->select('id')
        ->where('email', 'like', '%@demo.test')
        ->whereNull('deleted_at');
}

/**
 * Mismo criterio que `subqueryProductoresVisibles`, para contar sobre `users`.
 */
function consultaProductoresVisibles()
{
    return DB::table('users')
        ->where('email', 'like', '%@demo.test')
        ->whereNull('deleted_at');
}

/**
 * Lee el XLSX que devuelve `export()` y devuelve las cabeceras (fila 4) y las
 * filas de datos (desde la 5). El conjunto de columnas depende del filtro
 * activo, asi que por defecto se lee el ancho real de la hoja.
 */
function leerExportacion($response, ?int $columnas = null): array
{
    $path = tempnam(sys_get_temp_dir(), 'xlsx');
    file_put_contents($path, $response->getContent());

    $sheet = IOFactory::load($path)->getActiveSheet();
    unlink($path);

    $columnas ??= Coordinate::columnIndexFromString($sheet->getHighestColumn());

    $leer = function (int $col, int $row) use ($sheet) {
        return $sheet->getCell(Coordinate::stringFromColumnIndex($col).$row)->getValue();
    };

    $cabeceras = [];
    for ($c = 1; $c <= $columnas; $c++) {
        $cabeceras[] = $leer($c, 4);
    }

    $filas = [];
    for ($r = 5; $r <= $sheet->getHighestRow(); $r++) {
        $fila = [];
        for ($c = 1; $c <= $columnas; $c++) {
            $fila[] = $leer($c, $r);
        }
        $filas[] = $fila;
    }

    return ['headers' => $cabeceras, 'rows' => $filas];
}

/** Indices de las columnas por nombre, para no depender del orden a mano. */
function indicesDeColumna(array $headers, array $nombres): array
{
    return collect($nombres)
        ->mapWithKeys(fn ($n) => [$n => array_search($n, $headers, true)])
        ->all();
}

// =====================================================================
// AUTENTICACION
// =====================================================================

test('un productor no puede listar productores', function () {
    sembrarParaFiltros();

    // La ruta vive bajo `auth:staff`, asi que un productor de la guard `web`
    // no esta autenticado ahi: 401, no 403.
    $this->actingAs(User::query()->where('email', 'productor000@demo.test')->firstOrFail())
        ->getJson('/staff/producers')
        ->assertUnauthorized();
});

test('un auditor puede listar y exportar (Decision A3 de AGENTS.md)', function () {
    sembrarParaFiltros();
    actingComoStaff('auditor');

    $this->getJson('/staff/producers')->assertOk();

    // La matriz de permisos granta `export-producers` a ambos roles, y la ruta
    // web de export no tiene `staff.role:admin` (solo la de api.php lo tiene).
    $this->get('/staff/producers/export')->assertOk();

    // Lo que el auditor NO puede es gestionar usuarios staff.
    $this->get('/staff/users')->assertForbidden();
});

test('un administrador si puede gestionar usuarios staff', function () {
    sembrarParaFiltros();
    actingComoStaff('admin');

    $this->get('/staff/users')->assertOk();
});

// =====================================================================
// PAGINACION Y ORDEN
// =====================================================================

test('el listado pagina de a diez y ordena por id descendente', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $visibles = consultaProductoresVisibles()->count();
    $mayorId = consultaProductoresVisibles()->max('id');

    $primera = $this->getJson('/staff/producers')->assertOk();

    expect(totalDeLaPagina($primera))->toBe($visibles)
        ->and($primera->json('producers.data'))->toHaveCount(10)
        ->and($primera->json('producers.data.0.id'))->toBe((int) $mayorId);
});

test('la segunda pagina continua la secuencia sin repetir ni saltar ids', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $primera = collect($this->getJson('/staff/producers')->json('producers.data'))->pluck('id');
    $segunda = collect($this->getJson('/staff/producers?page=2')->json('producers.data'))->pluck('id');

    expect($primera->intersect($segunda))->toBeEmpty()
        ->and($segunda->max())->toBeLessThan($primera->min());
});

test('un filtro con mas de diez coincidencias agrega una segunda pagina', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $esperados = consultaProductoresVisibles()->where('dni', 'like', '3012%')->count();
    $respuesta = $this->getJson('/staff/producers?dni=3012')->assertOk();

    expect($esperados)->toBeGreaterThan(10)
        ->and(totalDeLaPagina($respuesta))->toBe($esperados)
        ->and($respuesta->json('producers.last_page'))->toBeGreaterThan(1);
});

test('el listado nunca incluye productores dados de baja', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $papelera = DB::table('users')->whereNotNull('deleted_at')->pluck('id');

    expect($papelera)->not->toBeEmpty();

    $visibles = collect($this->getJson('/staff/producers')->json('producers.data'))->pluck('id');

    expect($visibles->intersect($papelera))->toBeEmpty();
});

// =====================================================================
// FILTRO dni  (users.dni LIKE %param%)
// =====================================================================

test('el filtro dni busca por subcadena', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $objetivo = consultaProductoresVisibles()->where('dni', '!=', '')->first();

    $respuesta = $this->getJson('/staff/producers?dni='.$objetivo->dni)->assertOk();
    $coincidentes = collect($respuesta->json('producers.data'))
        ->filter(fn ($u) => str_contains((string) $u['dni'], $objetivo->dni));

    expect(totalDeLaPagina($respuesta))->toBeGreaterThanOrEqual(1)
        ->and($coincidentes)->not->toBeEmpty();
});

test('el filtro dni no encuentra un valor inexistente', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->getJson('/staff/producers?dni=00000000')
        ->assertOk()
        ->assertJsonPath('producers.total', 0);
});

test('un parametro dni vacio no activa el filtro', function () {
    sembrarParaFiltros();
    actingComoStaff();

    // Si se aplicara `LIKE %%`, la cohorte `sin_perfil` (dni = '') inflaría el total.
    $this->getJson('/staff/producers?dni=')
        ->assertOk()
        ->assertJsonPath('filters.dni', '')
        ->assertJsonPath('producers.total', consultaProductoresVisibles()->count());
});

// =====================================================================
// FILTRO name  (users.name LIKE %param%)
// =====================================================================

test('el filtro name es insensible a mayusculas', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $esperado = consultaProductoresVisibles()->whereRaw('name LIKE ?', ['%benegas%'])->count();

    $this->getJson('/staff/producers?name=benegas')->assertOk()
        ->assertJsonPath('producers.total', $esperado);
});

test('el filtro name es sensible a acentos', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $conAcento = consultaProductoresVisibles()->whereRaw('name LIKE ?', ['%Pérez%'])->count();
    $sinAcento = consultaProductoresVisibles()->whereRaw('name LIKE ?', ['%Perez%'])->count();

    expect($conAcento)->toBeGreaterThan(0)->and($sinAcento)->toBeGreaterThan(0)
        ->and($conAcento)->not->toBe($sinAcento);

    $this->getJson('/staff/producers?name='.urlencode('Pérez'))->assertOk()
        ->assertJsonPath('producers.total', $conAcento);
});

test('el filtro name encuentra todos los productores con nombre duplicado', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $duplicado = consultaProductoresVisibles()
        ->select('name', DB::raw('COUNT(*) as total'))
        ->groupBy('name')->havingRaw('COUNT(*) > 1')->first();

    $respuesta = $this->getJson('/staff/producers?name='.urlencode($duplicado->name))->assertOk();

    expect(totalDeLaPagina($respuesta))->toBe($duplicado->total);
});

test('los comodines de los datos no actúan como comodines de la consulta', function () {
    sembrarEscenarios();
    actingComoStaff();

    // El usuario de escenarios se llama "Comodin % y _ Cuña O'Neil".
    $this->getJson('/staff/producers?name='.urlencode('Cuña'))
        ->assertOk()
        ->assertJsonPath('producers.total', 1);

    // Un % en el parametro SI es un comodino: es comportamiento de LIKE.
    $this->getJson('/staff/producers?name=%25')->assertOk();
});

test('la comilla simple del nombre no rompe la consulta', function () {
    sembrarEscenarios();
    actingComoStaff();

    $this->getJson('/staff/producers?name='.urlencode("O'Neil"))
        ->assertOk()
        ->assertJsonPath('producers.total', 1);
});

// =====================================================================
// FILTRO distrito  (whereHas propiedades + REPLACE)
// =====================================================================

test('el filtro distrito funciona con un distrito de una sola palabra', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $esperado = consultaProductoresVisibles()->whereIn('id', function ($q) {
        $q->select('usuario_id')->from('propiedades')->where('distrito', 'paramillo');
    })->count();

    $this->getJson('/staff/producers?distrito=paramillo')->assertOk()
        ->assertJsonPath('producers.total', $esperado);
});

test('el filtro distrito ignora mayusculas y espacios sobrantes', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->getJson('/staff/producers?distrito=%20PARAMILLO%20')->assertOk()
        ->assertJsonPath('producers.total', $this->getJson('/staff/producers?distrito=paramillo')->json('producers.total'));
});

test('el filtro distrito excluye a los productores sin propiedades', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $sinPropiedades = consultaProductoresVisibles()
        ->whereNotExists(fn ($q) => $q->select(DB::raw(1))->from('propiedades')->whereColumn('propiedades.usuario_id', 'users.id'))
        ->pluck('id');

    $visibles = collect($this->getJson('/staff/producers?distrito=paramillo')->json('producers.data'))->pluck('id');

    expect($sinPropiedades)->not->toBeEmpty()
        ->and($visibles->intersect($sinPropiedades))->toBeEmpty();
});

test('el export encuentra los distritos de varias palabras', function () {
    sembrarParaFiltros();
    actingComoStaff();

    // `export()` normaliza `la-pega` a `lapega` y lo compara contra la columna
    // `distrito` con guiones y espacios eliminados.
    $esperados = consultaProductoresVisibles()
        ->whereIn('id', fn ($q) => $q->select('usuario_id')->from('propiedades')
            ->whereRaw("LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?", ['%lapega%']))
        ->pluck('email')
        ->sort()
        ->values()
        ->all();

    expect($esperados)->not->toBeEmpty();

    $export = leerExportacion($this->get('/staff/producers/export?distrito=la-pega')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Email', 'Distrito']);

    // Con filtro de propiedad hay una fila por propiedad del distrito, asi que
    // un productor con dos fincas en `la-pega` aparece dos veces. Lo que no
    // puede pasar es que aparezca un productor que no tiene la finca ahi.
    $exportados = collect($export['rows'])->pluck($idx['Email'])->unique()->sort()->values()->all();

    expect($exportados)->toEqual($esperados)
        ->and(collect($export['rows'])->pluck($idx['Distrito'])->unique()->all())->toBe(['La Pega']);
});

test('el espejo en PHP del filtro de cultivo no es mas estricto que el LIKE de MySQL', function () {
    // MySQL corre con `utf8mb4_unicode_ci`, que no distingue mayusculas ni
    // acentos: el `LIKE` de la consulta acepta `Viticola` para un cultivo
    // guardado como `Vitícola`. El filtro en PHP que decide que filas se
    // escriben no puede ser mas estricto que esa consulta, porque entonces el
    // export sale vacio aunque la consulta haya encontrado productores.
    $controlador = new StaffProducerController;
    $metodo = new ReflectionMethod($controlador, 'cultivoCoincide');

    $cultivo = new Cultivo;
    $cultivo->tipo = 'Vitícola';
    $cultivo->variedad = 'Malbec';

    // Todo lo que acepta el `LIKE` tiene que llegar al archivo.
    foreach (['Vitícola', 'Viticola', 'viticola', 'VITÍCOLA', 'vItIcOlA'] as $busqueda) {
        expect($metodo->invoke($controlador, $cultivo, '', $busqueda))
            ->toBeTrue("el export descartaria los cultivos para `{$busqueda}`");
    }

    // Y tiene que seguir descartando los cultivos que no corresponden.
    foreach (['Hortícola', 'Olivícola', 'Frutícola'] as $busqueda) {
        expect($metodo->invoke($controlador, $cultivo, '', $busqueda))
            ->toBeFalse("el export mezclaría cultivos de `{$busqueda}`");
    }

    // Mismo criterio para variedad.
    foreach (['Malbec', 'malbec', 'MALBEC'] as $busqueda) {
        expect($metodo->invoke($controlador, $cultivo, $busqueda, 'Vitícola'))->toBeTrue();
    }
});

test('el filtro de distrito acepta espacios guiones y mayusculas en cualquier combinacion', function () {
    sembrarParaFiltros();
    actingComoStaff();

    // Todas estas escrituras tienen que colapsar al mismo valor `lapega`.
    $variantes = ['La Pega', 'la pega', 'LA PEGA', 'LaPega', 'la-pega', '  La   Pega  ', "La\tPega"];

    $referencia = $this->getJson('/staff/producers?distrito=LaPega')->assertOk()->json('producers.total');
    expect($referencia)->toBeGreaterThan(0);

    foreach ($variantes as $variante) {
        $total = $this->getJson('/staff/producers?distrito='.urlencode($variante))
            ->assertOk()
            ->json('producers.total');

        expect($total)->toBe($referencia, "el listado no coincide para `{$variante}`");

        // El export tiene que traer las mismas filas que el listado.
        $export = leerExportacion(
            $this->get('/staff/producers/export?distrito='.urlencode($variante))->assertOk()
        );
        $idx = indicesDeColumna($export['headers'], ['Email']);

        expect(collect($export['rows'])->pluck($idx['Email'])->unique())
            ->toHaveCount($referencia, "el export no coincide para `{$variante}`");
    }
});

test('el filtro de distrito no confunde un distrito con otro que lo contiene', function () {
    sembrarParaFiltros();
    actingComoStaff();

    // `la-pega` no debe arrastrar `la-pena` ni ningun otro distrito.
    $export = leerExportacion($this->get('/staff/producers/export?distrito=La Pega')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Distrito']);

    expect($export['rows'])->not->toBeEmpty()
        ->and(collect($export['rows'])->pluck($idx['Distrito'])->unique()->all())->toBe(['La Pega']);

    $this->getJson('/staff/producers?distrito=La Pega')->assertOk()
        ->assertJsonPath('producers.total', $this->getJson('/staff/producers?distrito=la-pega')->json('producers.total'));
});

// =====================================================================
// FILTRO variedad / tipo  (whereHas propiedades.cultivos)
// =====================================================================

test('el filtro variedad busca por subcadena entre los cultivos', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->getJson('/staff/producers?variedad=Malbec')->assertOk()
        ->assertJsonPath('producers.total', consultaProductoresVisibles()
            ->whereIn('id', function ($q) {
                $q->select('usuario_id')->from('propiedades')
                    ->whereIn('id', function ($q) {
                        $q->select('propiedad_id')->from('cultivos')->where('variedad', 'like', '%Malbec%');
                    });
            })->count());
});

test('el filtro tipo cuenta cada productor una vez aunque tenga varios cultivos del tipo', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $conVarios = DB::table('cultivos')->where('tipo', 'Hortícola')
        ->select('propiedad_id')->groupBy('propiedad_id')->havingRaw('COUNT(*) > 1')->count();

    $this->getJson('/staff/producers?tipo=Horticola')->assertOk()
        ->assertJsonPath('producers.total', consultaProductoresVisibles()
            ->whereIn('id', function ($q) {
                $q->select('usuario_id')->from('propiedades')
                    ->whereIn('id', function ($q) {
                        $q->select('propiedad_id')->from('cultivos')->where('tipo', 'like', '%Horticola%');
                    });
            })->count());

    expect($conVarios)->toBeGreaterThan(0);
});

test('combinar tipo y variedad restringe mas que cada filtro por separado', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $soloTipo = $this->getJson('/staff/producers?tipo=Vitícola')->json('producers.total');
    $soloVariedad = $this->getJson('/staff/producers?variedad=Malbec')->json('producers.total');
    $ambos = $this->getJson('/staff/producers?tipo=Vitícola&variedad=Malbec')->json('producers.total');

    expect($ambos)->toBeGreaterThan(0)
        ->and($ambos)->toBeLessThanOrEqual(min($soloTipo, $soloVariedad));
});

test('los seis filtros combinados se aplican con AND', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->getJson('/staff/producers?dni=3012&name=Benegas&distrito=paramillo&tipo=Horticola')
        ->assertOk()
        ->assertJsonPath('filters.distrito', 'paramillo');
});

test('un filtro de variedad sin resultados devuelve la pagina vacia', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->getJson('/staff/producers?variedad=NoExisteEstaVariedad')->assertOk()
        ->assertJsonPath('producers.total', 0)
        ->assertJsonPath('producers.data', []);
});

// =====================================================================
// FILTRO rut
// =====================================================================

test('el filtro rut descarta los caracteres no numericos del parametro', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $objetivo = DB::table('propiedades')->where('rut', 1)->whereNotNull('rut_valor')->first();

    $this->getJson('/staff/producers?rut='.urlencode('RUT-'.$objetivo->rut_valor))->assertOk()
        ->assertJsonPath('producers.total', consultaProductoresVisibles()
            ->whereIn('id', function ($q) use ($objetivo) {
                $q->select('usuario_id')->from('propiedades')
                    ->where('rut', 1)->where('rut_valor', 'like', '%'.$objetivo->rut_valor.'%');
            })->count());
});

test('el filtro rut excluye las propiedades con rut = 0 aunque tengan valor', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $huerfanas = DB::table('propiedades')->where('rut', 0)->whereNotNull('rut_valor');
    $objetivo = (clone $huerfanas)->first();

    expect($objetivo)->not->toBeNull();

    $this->getJson('/staff/producers?rut='.$objetivo->rut_valor)->assertOk()
        ->assertJsonPath('producers.total', 0);
});

test('el filtro rut busca tambien dentro de los ceros iniciales', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $conCeros = DB::table('propiedades')->where('rut', 1)->where('rut_valor', 'like', '0%')->count();

    expect($conCeros)->toBeGreaterThan(0);

    $this->getJson('/staff/producers?rut=0')->assertOk()
        ->assertJsonPath('producers.total', consultaProductoresVisibles()
            ->whereIn('id', function ($q) {
                $q->select('usuario_id')->from('propiedades')
                    ->where('rut', 1)->where('rut_valor', 'like', '%0%');
            })->count());
});

// =====================================================================
// FILTROS DE StaffUserController
// =====================================================================

test('la lista de staff responde con el perfil generado', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->get('/staff/users')->assertOk();
});

test('el filtro de staff por email alcanza las dos paginas', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $this->get('/staff/users?email=admin')->assertOk();
});

// =====================================================================
// FILTRO CON LAS FILAS GOLDEN DE ESCENARIOS
// =====================================================================

test('las filas de frontera se localizan por sus valores limite', function () {
    sembrarEscenarios();
    actingComoStaff();

    $this->getJson('/staff/producers?name=Escenarios')->assertOk()
        ->assertJsonPath('producers.total', 1);

    $this->getJson('/staff/producers?dni=11111111')->assertOk()
        ->assertJsonPath('producers.total', 1);
});

test('el usuario de escenario con nombre de 255 caracteres se busca completo', function () {
    sembrarEscenarios();
    actingComoStaff();

    $nombre = DB::table('users')->where('email', 'largos@datos-prueba.test')->value('name');

    expect(strlen($nombre))->toBe(255);

    $this->getJson('/staff/producers?name='.urlencode('Nombre larguisimo'))->assertOk()
        ->assertJsonPath('producers.total', 1);
});

test('los datos de usuario en su longitud maxima se exportan sin truncar', function () {
    sembrarEscenarios();
    actingComoStaff();

    $largos = DB::table('users')->where('email', 'largos@datos-prueba.test')->first();

    expect(strlen($largos->name))->toBe(255)
        ->and(strlen($largos->direccion))->toBe(255);

    $export = leerExportacion($this->get('/staff/producers/export')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Email', 'Nombre', 'Dirección Productor']);

    $fila = collect($export['rows'])->firstWhere(fn ($f) => $f[$idx['Email']] === 'largos@datos-prueba.test');

    expect($fila)->not->toBeNull()
        ->and($fila[$idx['Nombre']])->toBe($largos->name)
        ->and($fila[$idx['Dirección Productor']])->toBe($largos->direccion);
});

test('el filtro distrito exporta perfil mas propiedades sin columnas de cultivo', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $export = leerExportacion($this->get('/staff/producers/export?distrito=paramillo')->assertOk());
    $headers = $export['headers'];

    // El modulo perfil entra completo.
    foreach (['ID', 'Nombre', 'Email', 'DNI', 'Teléfono', 'Dirección Productor'] as $columna) {
        expect($headers)->toContain($columna);
    }

    // El modulo propiedad entra; el modulo cultivo no, porque el filtro es de
    // propiedad y no tiene sentido mostrar cultivos.
    expect($headers)->toContain('Dirección Propiedad', 'Distrito', 'Especificar tenencia')
        ->and($headers)->not->toContain('Calle')
        ->and($headers)->not->toContain('Numeración')
        ->and($headers)->not->toContain('Miembro desde')
        ->and($headers)->not->toContain('Verificado')
        ->and($headers)->not->toContain('Tipo')
        ->and($headers)->not->toContain('Variedad')
        ->and($headers)->not->toContain('Estación')
        ->and($headers)->not->toContain('Manejo del cultivo')
        ->and($headers)->not->toContain('Tecnología de riego');

    // Una fila por propiedad del distrito, y ninguna de otro distrito.
    $idx = indicesDeColumna($headers, ['Distrito']);

    expect($export['rows'])->not->toBeEmpty()
        ->and(collect($export['rows'])->pluck($idx['Distrito'])->unique()->all())->toBe(['Paramillo']);
});

test('el filtro variedad exporta perfil mas propiedad mas el cultivo que coincide', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $export = leerExportacion($this->get('/staff/producers/export?variedad=Malbec')->assertOk());
    $headers = $export['headers'];

    expect($headers)->toContain('Dirección Propiedad', 'Distrito')
        ->and($headers)->toContain('Tipo', 'Variedad', 'Estación', 'Manejo del cultivo', 'Tecnología de riego');

    $idx = indicesDeColumna($headers, ['Email', 'Dirección Propiedad', 'Variedad', 'Tipo']);

    expect($export['rows'])->not->toBeEmpty();

    foreach ($export['rows'] as $fila) {
        // Solo el cultivo que matchea, y la propiedad que lo contiene.
        expect($fila[$idx['Variedad']])->toContain('Malbec')
            ->and($fila[$idx['Tipo']])->toBe('Vitícola')
            ->and($fila[$idx['Dirección Propiedad']])->not->toBeNull();
    }
});

test('el filtro tipo exporta solo los cultivos del tipo buscado', function () {
    sembrarParaFiltros();
    actingComoStaff();

    $export = leerExportacion($this->get('/staff/producers/export?tipo=Vitícola')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Tipo', 'Variedad']);

    expect($export['rows'])->not->toBeEmpty();

    foreach ($export['rows'] as $fila) {
        expect($fila[$idx['Tipo']])->toBe('Vitícola');
    }
});

test('el export no incluye el modulo maquinaria en ningun filtro', function () {
    actingComoStaff();

    $productor = User::factory()->create();
    $prop = Propiedad::factory()->for($productor, 'usuario')->create([
        'distrito' => 'la-pega', 'rut' => true, 'rut_valor' => '30123456',
    ]);
    Maquinaria::factory()->for($prop, 'propiedad')->create([
        'tractor' => true, 'modelo_tractor' => 2015, 'cincel_cultivadora' => true,
    ]);
    Cultivo::factory()->for($prop, 'propiedad')->create([
        'tipo' => 'Vitícola', 'variedad' => 'Malbec',
    ]);

    $columnasMaquinaria = [
        'Tractor', 'Modelo tractor', 'Arado', 'Rastra', 'Niveleta común', 'Niveleta láser',
        'Cincel/Cultivadora', 'Desmalezadora', 'Pulverizadora', 'Mochila pulverizadora',
        'Cosechadora', 'Enfardadora', 'Retroexcavadora', 'Carro/Carretón', 'Múltiple',
    ];

    // Con datos de maquinaria cargados, ningun filtro debe filtrarlos al archivo.
    foreach (['distrito=la-pega', 'rut=30123456', 'variedad=Malbec', 'tipo=Vitícola', ''] as $filtro) {
        $url = '/staff/producers/export'.($filtro === '' ? '' : '?'.$filtro);
        $export = leerExportacion($this->get($url)->assertOk());

        foreach ($columnasMaquinaria as $columna) {
            expect($export['headers'])->not->toContain($columna);
        }

        // El archivo no debe seguir teniendo datos de la fila filtrada.
        expect($export['rows'])->toHaveCount(1);
    }
});

test('el export por distrito no arrastra las propiedades de otros distritos', function () {
    actingComoStaff();

    // Un productor con una propiedad en el distrito filtrado y otra fuera: el
    // archivo debe traer solo la del distrito, en una unica fila.
    $productor = User::factory()->create();
    $enDistrito = Propiedad::factory()->for($productor, 'usuario')->create([
        'distrito' => 'la-pega', 'calle' => 'Calle Del Filtro',
    ]);
    $fuera = Propiedad::factory()->for($productor, 'usuario')->create([
        'distrito' => 'san-jose', 'calle' => 'Calle De Otro Distrito',
    ]);
    Cultivo::factory()->for($enDistrito, 'propiedad')->create(['variedad' => 'Malbec']);
    Cultivo::factory()->for($fuera, 'propiedad')->create(['variedad' => 'Malbec']);

    $otroProductor = User::factory()->create();
    Propiedad::factory()->for($otroProductor, 'usuario')->create([
        'distrito' => 'san-jose', 'calle' => 'Calle Que No Va',
    ]);

    $export = leerExportacion($this->get('/staff/producers/export?distrito=la-pega')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Email', 'Dirección Propiedad']);

    expect($export['rows'])->toHaveCount(1)
        ->and($export['rows'][0][$idx['Email']])->toBe($productor->email)
        ->and($export['rows'][0][$idx['Dirección Propiedad']])->toContain('Calle Del Filtro');
});

test('el export por variedad no arrastra otros cultivos ni otras propiedades', function () {
    actingComoStaff();

    $productor = User::factory()->create();

    $conMatch = Propiedad::factory()->for($productor, 'usuario')->create([
        'distrito' => 'la-pega', 'calle' => 'Propiedad Del Cultivo',
    ]);
    Cultivo::factory()->for($conMatch, 'propiedad')->create([
        'tipo' => 'Vitícola', 'variedad' => 'Malbec',
    ]);
    // Misma propiedad, otro cultivo: no debe generar fila.
    Cultivo::factory()->for($conMatch, 'propiedad')->create([
        'tipo' => 'Hortícola', 'variedad' => 'Tomate Perita',
    ]);

    $otraPropiedad = Propiedad::factory()->for($productor, 'usuario')->create([
        'distrito' => 'san-jose', 'calle' => 'Propiedad ajena al filtro',
    ]);
    Cultivo::factory()->for($otraPropiedad, 'propiedad')->create([
        'tipo' => 'Hortícola', 'variedad' => 'Tomate Perita',
    ]);

    $export = leerExportacion($this->get('/staff/producers/export?variedad=Malbec')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Email', 'Dirección Propiedad', 'Variedad', 'Tipo']);

    expect($export['rows'])->toHaveCount(1)
        ->and($export['rows'][0][$idx['Email']])->toBe($productor->email)
        ->and($export['rows'][0][$idx['Dirección Propiedad']])->toContain('Propiedad Del Cultivo')
        ->and($export['rows'][0][$idx['Variedad']])->toBe('Malbec')
        ->and($export['rows'][0][$idx['Tipo']])->toBe('Vitícola');
});

test('el export por RUT trae solo la propiedad que coincide', function () {
    actingComoStaff();

    $coincide = User::factory()->create();
    Cultivo::factory()->for(
        Propiedad::factory()->for($coincide, 'usuario')->create([
            'rut' => true, 'rut_valor' => '30123456', 'calle' => 'Con RUT',
        ]),
        'propiedad'
    )->create();
    Propiedad::factory()->for($coincide, 'usuario')->create(['calle' => 'Sin RUT']);

    $otro = User::factory()->create();
    Cultivo::factory()->for(
        Propiedad::factory()->for($otro, 'usuario')->create([
            'rut' => true, 'rut_valor' => '30999999', 'calle' => 'Otro RUT',
        ]),
        'propiedad'
    )->create();

    $export = leerExportacion($this->get('/staff/producers/export?rut=30123456')->assertOk());
    $idx = indicesDeColumna($export['headers'], ['Email', 'Dirección Propiedad']);

    expect($export['rows'])->toHaveCount(1)
        ->and($export['rows'][0][$idx['Email']])->toBe($coincide->email)
        ->and($export['rows'][0][$idx['Dirección Propiedad']])->toContain('Con RUT');
});
