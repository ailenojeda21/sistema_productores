<?php

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Las factories por defecto deben producir filas que ya respetan las reglas
 * de `Store*Request`. Si no, cualquier test que use `Model::factory()` hereda
 * datos que la app real rechazaría y el fallo aparece lejos de su causa.
 */
test('PropiedadFactory respeta derecho de riego, RUT y tenencia', function () {
    $propiedades = Propiedad::factory()->count(50)->create();

    foreach ($propiedades as $propiedad) {
        // `tipo_derecho_riego` solo es obligatorio cuando hay derecho de riego.
        if ($propiedad->derecho_riego) {
            expect($propiedad->tipo_derecho_riego)->not->toBeNull();
        } else {
            expect($propiedad->tipo_derecho_riego)->toBeNull();
        }

        expect($propiedad->tipo_tenencia)->not->toBeNull();
    }
});

test('PropiedadFactory deja el tipo de derecho de riego en null sin riego', function () {
    $propiedades = Propiedad::factory()->count(50)->create();

    // El default es aleatorio, así que se fuerzan los dos casos con estados.
    $sinRiego = Propiedad::factory()->create(['derecho_riego' => false, 'tipo_derecho_riego' => null]);
    $conRiego = Propiedad::factory()->create(['derecho_riego' => true, 'tipo_derecho_riego' => 'Subterráneo']);

    expect($sinRiego->tipo_derecho_riego)->toBeNull()
        ->and($conRiego->tipo_derecho_riego)->toBe('Subterráneo')
        ->and($propiedades)->toHaveCount(50);
});

test('PropiedadFactory cumple la regla required_if de tipo_tenencia', function () {
    $otros = Propiedad::factory()
        ->create(['tipo_tenencia' => 'otros', 'especificar_tenencia' => 'Usufructo familiar']);

    $propietario = Propiedad::factory()->create(['tipo_tenencia' => 'propietario']);

    expect($otros->especificar_tenencia)->not->toBeEmpty()
        ->and($propietario->especificar_tenencia)->toBeNull();
});

test('PropiedadFactory respeta la relacion entre rut, rut_valor y hectareas_malla', function () {
    foreach (Propiedad::factory()->count(50)->create() as $propiedad) {
        if ($propiedad->rut) {
            expect($propiedad->rut_valor)->not->toBeNull();
        } else {
            expect($propiedad->rut_valor)->toBeNull();
        }

        if ($propiedad->hectareas_malla !== null) {
            expect($propiedad->hectareas_malla)->toBeLessThanOrEqual($propiedad->hectareas);
        }
    }
});

test('los estados limite de PropiedadFactory caen dentro de las reglas', function () {
    $sinHa = Propiedad::factory()->sinHectareas()->create();
    $maxHa = Propiedad::factory()->conHectareasMaximas()->create();
    $rutLargo = Propiedad::factory()->conRutLargo()->create();
    $mallaVacia = Propiedad::factory()->conMallaSinValor()->create();
    $huerfano = Propiedad::factory()->conRutHuerfano()->create();
    $libre = Propiedad::factory()->conDistritoLibre('Zona-Norte-Lavalle')->create();

    // `hectareas` tiene cast `decimal:2`, así que llega como string.
    expect((float) $sinHa->hectareas)->toBe(0.0)
        ->and((float) $maxHa->hectareas)->toBe(1000.0)
        ->and($rutLargo->rut_valor)->toHaveLength(15)
        ->and($mallaVacia->malla)->toBeTrue()
        ->and($mallaVacia->hectareas_malla)->toBeNull()
        ->and($huerfano->rut)->toBeFalse()
        ->and($huerfano->rut_valor)->not->toBeNull()
        ->and($libre->distrito)->toBe('Zona-Norte-Lavalle');

    $extrema = Propiedad::factory()->conCoordenadasExtremas()->create();

    expect($extrema->lat)->toBeBetween(-90, 90)
        ->and($extrema->lng)->toBeBetween(-180, 180);
});

test('CultivoFactory mantiene el par tipo y variedad dentro del whitelist', function () {
    foreach (Cultivo::factory()->count(100)->create() as $cultivo) {
        expect(array_keys(Cultivo::getVariedadesForTipo($cultivo->tipo)))
            ->toContain($cultivo->variedad);
    }
});

test('CultivoFactory usa solo valores de los whitelists de la tabla', function () {
    $cultivos = Cultivo::factory()->count(100)->create();

    $tipos = $cultivos->pluck('tipo')->unique()->sort()->values()->all();
    $estaciones = $cultivos->pluck('estacion')->unique()->sort()->values()->all();
    $manejos = $cultivos->pluck('manejo_cultivo')->unique()->sort()->values()->all();
    $riegos = $cultivos->pluck('tecnologia_riego')->unique()->sort()->values()->all();

    $esperados = fn (array $mapa) => (function () use ($mapa) {
        $k = array_keys($mapa);
        sort($k);

        return $k;
    })();

    expect($tipos)->toBe($esperados(Cultivo::TIPOS))
        ->and($estaciones)->toBe($esperados(Cultivo::ESTACIONES))
        ->and($manejos)->toBe($esperados(Cultivo::MANEJO_OPTIONS))
        ->and($riegos)->toBe($esperados(Cultivo::TECNOLOGIA_RIEGO));
});

test('los estados de CultivoFactory aplican la override pedida', function () {
    $sinHa = Cultivo::factory()->sinHectareas()->create();
    $sinRiego = Cultivo::factory()->sinRiego()->create();
    $organico = Cultivo::factory()->organico()->create();
    $malbec = Cultivo::factory()->deTipo('Vitícola')->create();

    expect((float) $sinHa->hectareas)->toBe(0.0)
        ->and($sinRiego->tecnologia_riego)->toBeNull()
        ->and($organico->manejo_cultivo)->toBe('Organico')
        ->and($malbec->tipo)->toBe('Vitícola')
        ->and(array_keys(Cultivo::getVariedadesForTipo('Vitícola')))->toContain($malbec->variedad);
});

test('MaquinariaFactory cumple la regla required_if de modelo_tractor', function () {
    foreach (Maquinaria::factory()->count(50)->create() as $maquinaria) {
        if ($maquinaria->tractor) {
            expect($maquinaria->modelo_tractor)->toBeBetween(1900, (int) date('Y'));
        } else {
            expect($maquinaria->modelo_tractor)->toBeNull();
        }
    }
});

test('los estados de MaquinariaFactory dejan todos los implementos en false', function () {
    $minima = Maquinaria::factory()->minima()->create();

    foreach (array_keys(Maquinaria::IMPLEMENTOS_LABELS) as $flag) {
        expect((bool) $minima->$flag)->toBeFalse();
    }

    expect($minima->tractor)->toBeFalse()
        ->and($minima->modelo_tractor)->toBeNull();
});

test('ComercioFactory garantiza al menos una via de venta', function () {
    foreach (Comercio::factory()->count(50)->create() as $comercio) {
        $tieneMercados = ! empty($comercio->mercados);
        $tieneCooperativas = ! empty($comercio->cooperativas);

        expect($tieneMercados || $tieneCooperativas || $comercio->vende_en_finca)->toBeTrue();
    }
});

test('ComercioFactory guarda las claves de los mapas y no las etiquetas', function () {
    $comercio = Comercio::factory()->create();

    foreach ($comercio->mercados as $clave) {
        expect(array_keys(Comercio::MERCADOS))->toContain($clave);
    }

    foreach ($comercio->cooperativas as $clave) {
        expect(array_keys(Comercio::COOPERATIVAS))->toContain($clave);
    }
});

test('los estados de ComercioFactory cubren los casos borde del export', function () {
    $soloFinca = Comercio::factory()->soloEnFinca()->create();
    $sinMercados = Comercio::factory()->sinMercados()->create();
    $huerfano = Comercio::factory()->conMercadoHuerfano()->create();

    expect($soloFinca->mercados)->toBe([])
        ->and($soloFinca->vende_en_finca)->toBeTrue()
        ->and($sinMercados->mercados)->toBeNull()
        ->and($huerfano->mercados)->toBe(['Mercado Inexistente']);
});

test('UserFactory perfilCompleto alcanza el 100 por ciento', function () {
    $usuario = User::factory()->perfilCompleto()->create();

    expect($usuario->profile_completeness)->toBe(100);
});

test('UserFactory sinPerfil deja vacios los tres campos de perfil opcionales', function () {
    $usuario = User::factory()->sinPerfil()->create();

    // `name` y `email` son obligatorios, así que el mínimo alcanzable con
    // `profile_completeness` (name, email, dni, telefono, direccion) es 40.
    expect($usuario->dni)->toBe('')
        ->and($usuario->telefono)->toBe('')
        ->and($usuario->direccion)->toBe('')
        ->and($usuario->profile_completeness)->toBe(40);
});

test('UserFactory unverified deja el email sin verificar', function () {
    expect(User::factory()->unverified()->create()->email_verified_at)->toBeNull();
});

test('UserFactory eliminado pasa por el hook de anonimizacion', function () {
    $usuario = User::factory()->create();
    $email = $usuario->email;

    $usuario->delete();

    expect($usuario->fresh()->name)->toBe('Usuario eliminado')
        ->and($usuario->fresh()->email)->not->toBe($email)
        ->and($usuario->fresh()->deleted_at)->not->toBeNull();
});

test('los estados de StaffUserFactory cubren la matriz de roles', function () {
    $admin = StaffUser::factory()->admin()->create();
    $auditor = StaffUser::factory()->auditor()->create();
    $inactivo = StaffUser::factory()->inactivo()->create();
    $nunca = StaffUser::factory()->nuncaIngreso()->create();
    $eliminado = StaffUser::factory()->eliminado()->create();

    expect($admin->role)->toBe('admin')->and($admin->active)->toBeTrue()
        ->and($auditor->role)->toBe('auditor')
        ->and($inactivo->active)->toBeFalse()
        ->and($nunca->last_login_at)->toBeNull()
        ->and($eliminado->deleted_at)->not->toBeNull();
});

test('las 6 factories no dejan filas huerfanas', function () {
    $propiedad = Propiedad::factory()->create();
    Cultivo::factory()->create(['propiedad_id' => $propiedad->id]);
    Maquinaria::factory()->create(['propiedad_id' => $propiedad->id]);
    Comercio::factory()->create(['usuario_id' => $propiedad->usuario_id]);
    $usuarioId = $propiedad->usuario_id;

    // `on delete cascade` esta en el esquema, asi que al borrar la propiedad
    // deben caer los cultivos y la maquinaria; el comercio sobrevive porque
    // cuelga del usuario, no de la propiedad.
    $propiedad->delete();

    expect(DB::table('cultivos')->where('propiedad_id', $propiedad->id)->count())->toBe(0)
        ->and(DB::table('maquinarias')->where('propiedad_id', $propiedad->id)->count())->toBe(0)
        ->and(DB::table('comercios')->where('usuario_id', $usuarioId)->count())->toBe(1)
        ->and(Propiedad::find($propiedad->id))->toBeNull();
});
