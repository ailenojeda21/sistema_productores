<?php

namespace Database\Seeders\DatosPrueba;

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\User;
use App\Support\TestData\Contexto;
use App\Support\TestData\Plan;
use Illuminate\Database\Seeder;

/**
 * Filas "golden" escritas a mano, 1:1 con las aserciones de los tests.
 *
 * Estas filas NO se generan por azar: existen para poder afirmar en un test
 * que un valor limite es aceptado o rechazado por una regla de validacion
 * concreta, sin depender de la semilla.
 *
 * Pertenecen al usuario `escenarios@datos-prueba.test`, que agrupa todos los
 * extremos de validacion en un solo lugar.
 */
class EscenariosSeeder extends Seeder
{
    public const EMAIL = 'escenarios@datos-prueba.test';

    /** @var array<string, int> */
    private array $propiedades = [];

    private string $password = '';

    public function run(Plan $plan, Contexto $contexto): void
    {
        $this->password = $contexto->hash($plan->password);

        $this->purgar();
        $this->ejecutar();
    }

    private function ejecutar(): void
    {
        $usuario = $this->crearUsuario();

        $this->geografia($usuario->id);
        $this->hectareas($usuario->id);
        $this->rutYMalla($usuario->id);
        $this->maquinarias($usuario->id);
        $this->comercializacion($usuario->id);
        $this->longitudesMaximas($usuario->id);
        $this->wildcardsYEscapes();
        $this->sinVerificar();
    }

    private function purgar(): void
    {
        User::withTrashed()->where('email', 'like', '%@datos-prueba.test')->get()
            ->each->forceDelete();
    }

    private function crearUsuario(): User
    {
        return User::query()->create([
            'name' => 'Escenarios de Frontera',
            'email' => self::EMAIL,
            'password' => $this->password,
            'dni' => '11111111',
            'telefono' => '2614000000',
            'direccion' => 'Escenarios s/n',
        ]);
    }

    /* ------------------------------------------------------------- helpers */

    private function crearPropiedad(int $usuarioId, string $clave, array $atributos): int
    {
        $propiedad = new Propiedad;
        $propiedad->forceFill(array_merge([
            'usuario_id' => $usuarioId,
            'calle' => 'Escenario',
            'numeracion' => '1',
            'distrito' => 'paramillo',
            'hectareas' => 10.00,
            'tipo_tenencia' => 'propietario',
            'lat' => -32.9,
            'lng' => -68.8,
        ], $atributos))->save();

        $this->propiedades[$clave] = $propiedad->id;

        return $propiedad->id;
    }

    private function crearCultivo(string $propiedad, array $atributos): void
    {
        Cultivo::query()->create(array_merge([
            'propiedad_id' => $this->propiedades[$propiedad],
            'tipo' => 'Hortícola',
            'variedad' => 'Tomate Redondo',
            'estacion' => 'Verano',
            'hectareas' => 1.00,
            'manejo_cultivo' => 'Convencional',
            'tecnologia_riego' => 'Goteo',
        ], $atributos));
    }

    private function crearMaquinaria(string $propiedad, array $atributos): void
    {
        $maquinaria = new Maquinaria;
        $maquinaria->forceFill(array_merge([
            'propiedad_id' => $this->propiedades[$propiedad],
        ], $atributos))->save();
    }

    /* ----------------------------------------------------------- escenarios */

    /** `StorePropiedadRequest`: lat between:-90,90 · lng between:-180,180 */
    private function geografia(int $usuarioId): void
    {
        $this->crearPropiedad($usuarioId, 'geo_negoativo', ['lat' => -90, 'lng' => -180]);
        $this->crearPropiedad($usuarioId, 'geo_positivo', ['lat' => 90, 'lng' => 180]);
    }

    /** `hectareas`: min:0 · max:1000 · `hectareas_malla` con lte:hectareas */
    private function hectareas(int $usuarioId): void
    {
        // Cultivo de 0.00 ha: el `min:0` lo acepta.
        $this->crearPropiedad($usuarioId, 'hect_cero', ['hectareas' => 0.00]);
        $this->crearCultivo('hect_cero', ['hectareas' => 0.00]);

        $this->crearPropiedad($usuarioId, 'hect_maximo', ['hectareas' => 1000.00]);

        // `hectareas_disponibles == 0`: es el `max` dinamico que
        // `StoreCultivoRequest` inyecta en la regla de `hectareas`.
        $this->crearPropiedad($usuarioId, 'al_tope', ['hectareas' => 3.00]);
        $this->crearCultivo('al_tope', ['hectareas' => 1.50]);
        $this->crearCultivo('al_tope', ['hectareas' => 1.50]);
    }

    /** `rut_valor`: max:15 + regex:/^\d+$/ · `malla` sin valor y malla al total */
    private function rutYMalla(int $usuarioId): void
    {
        $this->crearPropiedad($usuarioId, 'rut_maximo', ['rut' => true, 'rut_valor' => '123456789012345']);
        $this->crearPropiedad($usuarioId, 'rut_minimo', ['rut' => true, 'rut_valor' => '123']);
        $this->crearPropiedad($usuarioId, 'rut_con_ceros', ['rut' => true, 'rut_valor' => '00001234567']);

        // Dato huerfano: `rut = 0` con `rut_valor` presente. El filtro `rut`
        // exige `rut = 1`, asi que debe excluirlo.
        $this->crearPropiedad($usuarioId, 'rut_huerfano', ['rut' => false, 'rut_valor' => '20000001']);

        // `malla = 1` con `hectareas_malla = NULL`: la regla `lte:hectareas` no
        // puede compararse contra NULL, asi que el detalle del productor debe
        // emitir la celda vacia sin error.
        $this->crearPropiedad($usuarioId, 'malla_sin_valor', [
            'malla' => true, 'hectareas_malla' => null, 'hectareas' => 12.00,
        ]);

        $this->crearPropiedad($usuarioId, 'malla_al_total', [
            'malla' => true, 'hectareas_malla' => 8.00, 'hectareas' => 8.00,
        ]);

        // `tipo_tenencia = otros` exige `especificar_tenencia` (required_if).
        $this->crearPropiedad($usuarioId, 'tenencia_otros', [
            'tipo_tenencia' => 'otros',
            'especificar_tenencia' => 'Usufructo familiar',
        ]);

        $this->crearPropiedad($usuarioId, 'tenencia_otros_sin_especificar', [
            'tipo_tenencia' => 'otros',
            'especificar_tenencia' => null,
        ]);
    }

    /** `modelo_tractor`: integer|min:1900|max:{date('Y')}, requerido si hay tractor */
    private function maquinarias(int $usuarioId): void
    {
        $this->crearMaquinaria('geo_negoativo', ['tractor' => true, 'modelo_tractor' => 1900]);

        $this->crearMaquinaria('geo_positivo', [
            'tractor' => true, 'modelo_tractor' => (int) date('Y'),
        ]);

        // `tractor = 0` exige `modelo_tractor = NULL`.
        $this->crearMaquinaria('hect_maximo', ['tractor' => false, 'modelo_tractor' => null]);

        $this->crearMaquinaria('al_tope', [
            'tractor' => true,
            'modelo_tractor' => 2015,
            'desmalezadora' => true,
            'pulverizadora_tractor' => true,
        ]);
    }

    /** Valores que `Rule::in()` rechazaria pero que la columna JSON admite */
    private function comercializacion(int $usuarioId): void
    {
        $comercio = new Comercio;
        $comercio->forceFill([
            'usuario_id' => $usuarioId,
            'infraestructura_empaque' => true,
            'vende_en_finca' => true,
            'mercados' => json_encode(['Mercado Inexistente'], JSON_UNESCAPED_UNICODE),
            'cooperativas' => json_encode(['Coop Maipú'], JSON_UNESCAPED_UNICODE),
        ])->save();
    }

    /** Longitudes maximas de los varchar de `propiedades` y `cultivos` */
    private function longitudesMaximas(int $usuarioId): void
    {
        $this->crearPropiedad($usuarioId, 'distrito_maximo', [
            'distrito' => str_pad('distrito', 100, '-largo'),
        ]);

        $this->crearCultivo('distrito_maximo', [
            'variedad' => str_pad('variedad', 255, '-larga'),
        ]);
    }

    /**
     * El filtro de `name` es `name LIKE "%{$param}%"`. Un `%` o un `_` en los
     * DATOS no debe comportarse como comodin, y una comilla no debe romper el
     * escapado de SQL.
     */
    private function wildcardsYEscapes(): void
    {
        User::query()->create([
            'name' => "Comodin % y _ Cuña O'Neil",
            'email' => 'comodines@datos-prueba.test',
            'password' => $this->password,
            'dni' => '22222222',
            'telefono' => '2614000002',
            'direccion' => "O'Neill 123",
        ]);

        // Los cinco campos de `profile_completeness` en su longitud maxima.
        User::query()->create([
            'name' => str_pad('Nombre larguisimo', 255, ' X'),
            'email' => 'largos@datos-prueba.test',
            'password' => $this->password,
            'dni' => str_pad('3', 20, '0'),
            'telefono' => str_pad('2', 20, '6'),
            'direccion' => str_pad('direccion ', 255, 'larga '),
        ]);
    }

    /** Email sin verificar con los cinco campos completos: perfil al 100%. */
    private function sinVerificar(): void
    {
        $usuario = User::query()->create([
            'name' => 'Perfil Completo Sin Verificar',
            'email' => 'completo-sin-verificar@datos-prueba.test',
            'password' => $this->password,
            'dni' => '33333333',
            'telefono' => '2614000003',
            'direccion' => 'Completa 456',
        ]);

        $usuario->forceFill(['email_verified_at' => null])->saveQuietly();
    }
}
