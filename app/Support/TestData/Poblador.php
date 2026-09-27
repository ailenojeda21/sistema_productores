<?php

namespace App\Support\TestData;

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Propiedad;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Construye el dataset de prueba completo en memoria.
 *
 * Criterios implementados (ver docs/datos_prueba.md):
 *   C1 cardinalidad · C2 unicidad · C3 determinismo · C4 dominios
 *   C5 distrito      · C6 temporal     · C7 similitud     · C8 paginación
 *   C9 sentinelas   · C10 exportabilidad
 *
 * Nada aquí escribe en la base: `DatosPruebaSeeder` persiste el plan.
 */
final class Poblador
{
    /* ------------------------------------------------------------------ tags */

    public const TAG_SIN_PERFIL = 'sin_perfil';

    public const TAG_NO_VERIFICADO = 'no_verificado';

    public const TAG_SIN_PROPIEDADES = 'sin_propiedades';

    public const TAG_PROPIEDAD_SIN_CULTIVOS = 'propiedad_sin_cultivos';

    public const TAG_SIN_MAQUINARIA = 'sin_maquinaria';

    public const TAG_SIN_COMERCIO = 'sin_comercio';

    public const TAG_SO_FINCA = 'comercializa_solo_finca';

    public const TAG_ORGANICO = 'organico';

    public const TAG_RIEGO_NULO = 'riego_nulo';

    public const TAG_DISTRITO_LIBRE = 'distrito_libre';

    public const TAG_NOMBRES_SIMILARES = 'nombres_similares';

    public const TAG_NOMBRE_DUPLICADO = 'nombre_duplicado';

    public const TAG_NOMBRE_DENSO = 'nombre_denso';

    public const TAG_PREFIJO_DNI = 'prefijo_dni';

    public const TAG_RUT = 'rut';

    public const TAG_RUT_CEROS = 'rut_ceros';

    public const TAG_RUT_HUERFANO = 'rut_huerfano';

    public const TAG_TENENCIA_OTROS = 'tenencia_otros';

    public const TAG_TENENCIA_ARRENDATARIO = 'tenencia_arrendatario';

    public const TAG_SIN_DERECHO_RIEGO = 'sin_derecho_riego';

    public const TAG_MULTI_PROPIEDAD = 'multi_propiedad';

    public const TAG_TRACTOR_FRONTERA = 'tractor_frontera';

    public const TAG_MAQUINARIA_MINIMA = 'maquinaria_minima';

    public const TAG_COMERCIO_JSON = 'comercio_json_extremo';

    public const TAG_VARIEDAD_TOMATE = 'variedad_tomate_perita';

    public const TAG_VARIEDAD_MALBEC = 'variedad_malbec';

    public const TAG_TIPO_OLIVICOLA = 'tipo_olivicola';

    public const TAG_TIPO_FRUTICOLA = 'tipo_fruticola';

    public const TAG_CULTIVO_EXACTO = 'cultivo_al_tope';

    public const TAG_CULTIVO_CERO = 'cultivo_cero_hectareas';

    public const TAG_ANTIGUO = 'antiguo';

    public const TAG_FRONTERA_30_DIAS = 'frontera_30_dias';

    public const TAG_SOFT_DELETED = 'soft_deleted';

    public const NOMBRE_DUPLICADO = 'Marta Elena Pérez';

    public const APELLIDO_DENSO = 'Benegas';

    /**
     * Grupos densos: 11 productores en el mismo distrito => 2 páginas de
     * `paginate(10)` y ejercitan el `distinct()` de `StaffProducerController`.
     *
     * Se eligen un distrito de un solo token (`paramillo`) y tres de varios
     * tokens, porque `index()` y `export()` normalizan el input de forma
     * distinta: el mismo `distrito=la pega` devuelve filas diferentes.
     *
     * @var array<int, string>
     */
    private const DISTRITOS_DENSOS = [
        'paramillo',
        'la-pega',
        'san-jose',
        'el-carmen',
    ];

    /**
     * Cohortes de cola: reservan los últimos índices del dataset para que el
     * resto de las cohortes por módulo conserven sus conteos.
     *
     * @var array<int, array{tag: string, cantidad: int}>
     */
    private const COLA = [
        ['tag' => self::TAG_SOFT_DELETED, 'cantidad' => 4],
        ['tag' => self::TAG_PROPIEDAD_SIN_CULTIVOS, 'cantidad' => 3],
        ['tag' => self::TAG_SIN_PROPIEDADES, 'cantidad' => 5],
        ['tag' => self::TAG_ANTIGUO, 'cantidad' => 5],
        ['tag' => self::TAG_FRONTERA_30_DIAS, 'cantidad' => 2],
    ];

    /**
     * Cohortes por módulo. `res` debe ser menor que `mod`.
     *
     * @var array<int, array{tag: string, mod: int, res: int}>
     */
    private const MODULOS = [
        ['tag' => self::TAG_PREFIJO_DNI, 'mod' => 7, 'res' => 0],
        ['tag' => self::TAG_VARIEDAD_TOMATE, 'mod' => 7, 'res' => 1],
        ['tag' => self::TAG_VARIEDAD_MALBEC, 'mod' => 7, 'res' => 3],
        ['tag' => self::TAG_RUT, 'mod' => 7, 'res' => 5],
        ['tag' => self::TAG_NOMBRE_DENSO, 'mod' => 7, 'res' => 6],
        ['tag' => self::TAG_TENENCIA_OTROS, 'mod' => 8, 'res' => 1],
        ['tag' => self::TAG_NOMBRES_SIMILARES, 'mod' => 9, 'res' => 1],
        ['tag' => self::TAG_ORGANICO, 'mod' => 9, 'res' => 4],
        ['tag' => self::TAG_SO_FINCA, 'mod' => 9, 'res' => 6],
        ['tag' => self::TAG_NO_VERIFICADO, 'mod' => 11, 'res' => 3],
        ['tag' => self::TAG_COMERCIO_JSON, 'mod' => 11, 'res' => 7],
        ['tag' => self::TAG_SIN_PERFIL, 'mod' => 12, 'res' => 10],
        ['tag' => self::TAG_RUT_HUERFANO, 'mod' => 13, 'res' => 6],
        ['tag' => self::TAG_RIEGO_NULO, 'mod' => 13, 'res' => 8],
        ['tag' => self::TAG_MAQUINARIA_MINIMA, 'mod' => 6, 'res' => 2],
        ['tag' => self::TAG_TRACTOR_FRONTERA, 'mod' => 17, 'res' => 4],
        ['tag' => self::TAG_RUT_CEROS, 'mod' => 19, 'res' => 3],
        ['tag' => self::TAG_SIN_DERECHO_RIEGO, 'mod' => 5, 'res' => 3],
        ['tag' => self::TAG_TENENCIA_ARRENDATARIO, 'mod' => 4, 'res' => 2],
        ['tag' => self::TAG_SIN_MAQUINARIA, 'mod' => 4, 'res' => 0],
        ['tag' => self::TAG_SIN_COMERCIO, 'mod' => 3, 'res' => 2],
        ['tag' => self::TAG_DISTRITO_LIBRE, 'mod' => 25, 'res' => 17],
        ['tag' => self::TAG_TIPO_OLIVICOLA, 'mod' => 23, 'res' => 2],
        ['tag' => self::TAG_TIPO_FRUTICOLA, 'mod' => 23, 'res' => 5],
        ['tag' => self::TAG_MULTI_PROPIEDAD, 'mod' => 29, 'res' => 11],
        ['tag' => self::TAG_CULTIVO_EXACTO, 'mod' => 31, 'res' => 0],
        ['tag' => self::TAG_CULTIVO_CERO, 'mod' => 31, 'res' => 14],
        ['tag' => self::TAG_NOMBRE_DUPLICADO, 'mod' => 33, 'res' => 5],
    ];

    /** @var array<int, string> */
    private const NOMBRES = [
        'Aarón', 'Abigail', 'Adrián', 'Agustina', 'Aída', 'Alan', 'Alba', 'Alejandro',
        'Alfonso', 'Alicia', 'Amparo', 'Andrés', 'Ángela', 'Aníbal', 'Antonella',
        'Ariel', 'Ayelén', 'Bárbara', 'Benicio', 'Bernarda', 'Bianca', 'Braian',
        'Brenda', 'Bruno', 'Camila', 'Carla', 'Carlos', 'Carmen', 'Cecilia', 'César',
        'Cielo', 'Claudio', 'Claudia', 'Cristian', 'Daniela', 'Dante', 'Débora',
        'Diego', 'Eduardo', 'Elena', 'Eliana', 'Emanuel', 'Emilia', 'Enzo', 'Esteban',
        'Estela', 'Ezequiel', 'Fabián', 'Facundo', 'Federico', 'Felipe', 'Fernanda',
        'Florencia', 'Franca', 'Gabriel', 'Genoveva', 'Germán', 'Gisela', 'Guido',
        'Hernán', 'Ignacia', 'Iván', 'Javiera', 'Jeremías', 'Joaquín', 'Julián',
        'Karina', 'Lautaro', 'Leonela', 'Lisandro', 'Lorenzo', 'Lucero', 'Luciana',
        'Ludmila', 'Luisa', 'Manuel', 'Marcela', 'Marcos', 'Mariana', 'Martina',
        'Mateo', 'Mercedes', 'Micaela', 'Mirta', 'Nahuel', 'Natalia', 'Néstor',
        'Nilda', 'Octavio', 'Olga', 'Orlando', 'Osvaldo', 'Pablo', 'Paloma',
        'Patricia', 'Pedro', 'Priscila', 'Ramiro', 'Raquel', 'Rebeca', 'Renata',
        'Rocío', 'Rodrigo', 'Romina', 'Rosa', 'Rubén', 'Sabrina', 'Salvador',
        'Sandra', 'Sara', 'Sergio', 'Silvia', 'Simón', 'Sofía', 'Soledad', 'Tamara',
        'Teodoro', 'Tomás', 'Ulises', 'Valentina', 'Valeria', 'Vicente', 'Violeta',
        'Waldo', 'Yolanda', 'Zacarías', 'Zulma',
    ];

    /** @var array<int, string> */
    private const APELLIDOS = [
        'Aguirre', 'Álvarez', 'Amaya', 'Andrada', 'Arenas', 'Ayala', 'Baeza', 'Benegas',
        'Blanco', 'Bustos', 'Caballero', 'Cáceres', 'Calderón', 'Campos', 'Carrasco',
        'Castillo', 'Castro', 'Chávez', 'Colombo', 'Contreras', 'Córdoba', 'Cruz',
        'Díaz', 'Domínguez', 'Duarte', 'Escobar', 'Espinosa', 'Fernández', 'Figueroa',
        'Flores', 'Franco', 'Gaitán', 'Gallardo', 'García', 'Giménez', 'Godoy', 'Gómez',
        'González', 'Guerrero', 'Gutiérrez', 'Guzmán', 'Hernández', 'Herrera', 'Ibáñez',
        'Ibarra', 'Juárez', 'Lamas', 'Ledesma', 'Leiva', 'López', 'Lucero', 'Maldonado',
        'Mansilla', 'Martínez', 'Mendoza', 'Molina', 'Montenegro', 'Morales', 'Muñoz',
        'Navarro', 'Núñez', 'Olivera', 'Ortega', 'Paredes', 'Parra', 'Paz', 'Pereira',
        'Pérez', 'Prieto', 'Quiroga', 'Ramírez', 'Reyes', 'Ríos', 'Rivero', 'Robles',
        'Rodríguez', 'Rojas', 'Romero', 'Salas', 'Salazar', 'Sánchez',
        'Sandoval', 'Sarmiento', 'Silva', 'Sosa', 'Suárez', 'Tapia', 'Torres', 'Valdez',
        'Vargas', 'Vera', 'Villafañe', 'Zalazar', 'Zeballos',
    ];

    /**
     * Pares casi idénticos con y sin tilde.
     *
     * `users.name` usa collation `utf8mb4_unicode_ci`: insensible a mayúsculas
     * pero SENSIBLE a acentos. Estos nombres separan ambos comportamientos.
     *
     * @var array<int, string>
     */
    private const NOMBRES_SIMILARES = [
        'Pérez, Marta Elena',
        'Perez, Marta Elena',
        'Pérez, Marta Estela',
        'Gómez, Ricardo',
        'Gomez, Ricardo',
        'Gómez, Raúl',
        'Rodríguez, Ana María',
        'Rodriguez, Ana Maria',
        'Muñoz, Sebastián',
        'Munoz, Sebastian',
        'Núñez, Fabián',
        'Nunez, Fabian',
    ];

    /** @var array<int, string> */
    private const CALLES = [
        'Av. San Martin',
        'Av. Alvarez Gonzalez',
        'Ruta Provincial 6',
        'Ruta Provincial 8',
        'Ruta Provincial 50',
        'Calle Francia',
        'Calle España',
        'Calle Bolivia',
        'Calle Chile',
        'Calle Zapiola',
        'Calle Pedro Guido',
        'Camino a El Carmen',
        'Camino a Jocoli',
        'Camino a Paramillo',
        'Camino a Villa Tulumaya',
        'Pasaje El Chical',
        'Pasaje Los Nogales',
        'Bajada del Cerro',
        'Barrio El Vergel',
        'Barrio La Palmera',
        'Camino Real',
        'Sindicato Agrícola',
    ];

    /**
     * Distritos fuera del whitelist de `Propiedad::DISTRITOS`.
     *
     * `StorePropiedadRequest` NO tiene regla `in:` para `distrito`, así que la
     * columna admite cualquier texto de hasta 100 caracteres. Estos valores
     * ejercitan el fallback de `distrito_label` y el `whereRaw` del filtro.
     *
     * @var array<int, string>
     */
    private const DISTRITOS_LIBRES = [
        'Zona-Norte-Lavalle',
        'Barrio 12 de Octubre',
        'Paraje El Zampal',
        'Tambillos (legacy)',
        'El Puesto de la Ruta',
    ];

    /** @var array<int, string> */
    private const TENENCIAS_OTRAS = [
        'Usufructo familiar',
        'Sucesión testamentaria',
        'Cooperativa de trabajo',
        'Concesión municipal',
        'Cesión de derechos',
    ];

    /** @var array<int, string> */
    private const PREFIJOS_DNI = ['3012'];

    /** @var array<int, string> */
    private const AVATARS = ['uno.png', 'dos.png', 'tres.png', 'cuatro.png', 'cinco.png'];

    private Rng $rng;

    private int $semilla;

    /**
     * Instante de referencia de todas las fechas del dataset.
     */
    private Carbon $referencia;

    /**
     * Pool ordenado de (tipo, variedad) y cursor compartido.
     *
     * Recorrerlo en orden garantiza que las 69 combinaciones del whitelist
     * aparezcan en el dataset (C4) sin depender del azar, manteniendo la
     * coherencia tipo <-> variedad.
     *
     * @var array<int, array{tipo: string, variedad: string}>
     */
    private array $poolVariedades;

    private int $cursorVariedad = 0;

    public function __construct(?int $semilla = null, ?string $referencia = null)
    {
        $this->semilla = $semilla ?? (int) config('datos-prueba.semilla', 20260905);
        $this->rng = new Rng($this->semilla);
        $this->poolVariedades = $this->construirPoolVariedades();
        $this->referencia = $referencia !== null
            ? Carbon::parse($referencia)
            : Carbon::parse((string) (config('datos-prueba.referencia') ?: Carbon::now()));
    }

    /**
     * @return array<int, array{tipo: string, variedad: string}>
     */
    private function construirPoolVariedades(): array
    {
        $pool = [];

        foreach (array_keys(Cultivo::TIPOS) as $tipo) {
            foreach (array_keys(Cultivo::getVariedadesForTipo($tipo)) as $variedad) {
                $pool[] = ['tipo' => $tipo, 'variedad' => $variedad];
            }
        }

        return $pool;
    }

    /**
     * @return array{tipo: string, variedad: string}
     */
    private function siguienteVariedad(): array
    {
        if ($this->cursorVariedad >= count($this->poolVariedades)) {
            $this->cursorVariedad = 0;
        }

        return $this->poolVariedades[$this->cursorVariedad++];
    }

    /* -------------------------------------------------------------- planning */

    public function plan(?string $perfil = null): Plan
    {
        $perfil ??= (string) config('datos-prueba.perfil', 'mediano');
        $cuotas = config("datos-prueba.perfiles.{$perfil}");

        if (! is_array($cuotas)) {
            throw new \InvalidArgumentException(
                "Perfil de datos de prueba desconocido: '{$perfil}'. Disponibles: ".
                implode(', ', array_keys((array) config('datos-prueba.perfiles', [])))
            );
        }

        $total = (int) $cuotas['usuarios'];
        [$cola, $base] = $this->repartirCola($total);
        $distritosDensos = $this->repartirDistritosDensos($base);

        $usuarios = [];

        for ($i = 0; $i < $total; $i++) {
            $usuarios[] = $this->componerUsuario(
                indice: $i,
                distritoDenso: $distritosDensos[$i] ?? null,
                tagCola: $cola[$i] ?? null,
            );
        }

        return new Plan(
            perfil: $perfil,
            semilla: $this->semilla,
            password: (string) config('datos-prueba.password', 'DatosPrueba2026!'),
            usuarios: $usuarios,
            staff: $this->componerStaff((int) $cuotas['staff']),
        );
    }

    /**
     * @return array{0: array<int, string>, 1: int} [tagsPorIndice, cantidadBase]
     */
    private function repartirCola(int $total): array
    {
        $plantilla = self::COLA;
        $plantilla[3]['cantidad'] = max($plantilla[3]['cantidad'], (int) round($total * 0.05));

        $asignadas = [];
        $inicio = $total;

        foreach (array_reverse($plantilla) as $cohorte) {
            $inicio -= $cohorte['cantidad'];

            for ($k = 0; $k < $cohorte['cantidad']; $k++) {
                $asignadas[$inicio + $k] = $cohorte['tag'];
            }
        }

        return [$asignadas, max(0, $inicio)];
    }

    /**
     * @return array<int, string>
     */
    private function repartirDistritosDensos(int $base): array
    {
        $denso = min(11, max(3, intdiv(max(1, $base), 7)));
        $asignados = [];
        $cursor = 0;

        foreach (self::DISTRITOS_DENSOS as $distrito) {
            for ($k = 0; $k < $denso; $k++) {
                $asignados[$cursor + $k] = $distrito;
            }

            $cursor += $denso;
        }

        return $asignados;
    }

    /**
     * @return array<int, string>
     */
    private function tagsDe(int $indice, ?string $tagCola): array
    {
        $tags = $tagCola !== null ? [$tagCola] : [];

        foreach (self::MODULOS as $modulo) {
            if ($indice % $modulo['mod'] === $modulo['res']) {
                $tags[] = $modulo['tag'];
            }
        }

        return array_values(array_unique($tags));
    }

    /**
     * @return array<string, mixed>
     */
    private function componerUsuario(int $indice, ?string $distritoDenso, ?string $tagCola): array
    {
        $tags = $this->tagsDe($indice, $tagCola);
        $contiene = fn (string $tag): bool => in_array($tag, $tags, true);

        $sinPropiedades = $contiene(self::TAG_SIN_PROPIEDADES);
        $propSinCultivos = $contiene(self::TAG_PROPIEDAD_SIN_CULTIVOS);
        $sinPerfil = $contiene(self::TAG_SIN_PERFIL);

        $propiedades = $sinPropiedades
            ? []
            : $this->componerPropiedades($indice, $distritoDenso, $tags, $propSinCultivos);

        return [
            'clave' => sprintf('u%03d', $indice),
            'indice' => $indice,
            'name' => $this->nombreDe($indice, $tags),
            'email' => sprintf('productor%03d@demo.test', $indice),
            'dni' => $sinPerfil ? '' : $this->dniDe($indice, $tags),
            'telefono' => $sinPerfil ? '' : $this->telefonoDe($indice),
            'direccion' => $sinPerfil ? '' : $this->direccionDe($indice),
            'avatar' => $indice % 6 === 5 ? null : $this->rng->elegir(self::AVATARS),
            'cooperativas' => $this->cooperativasDe($indice),
            'verificado' => ! $contiene(self::TAG_NO_VERIFICADO),
            'created_at' => $this->fechaDe($indice, $tags),
            'deleted_at' => $contiene(self::TAG_SOFT_DELETED)
                ? $this->referencia->copy()->subDays($this->rng->intEntre(1, 20))
                : null,
            'tags' => $tags,
            'propiedades' => $propiedades,
            'maquinarias' => $this->componerMaquinarias($propiedades, $tags),
            'comercio' => $this->componerComercio($indice, $tags),
        ];
    }

    /* -------------------------------------------------------------- usuario */

    /**
     * @param  array<int, string>  $tags
     */
    private function nombreDe(int $indice, array $tags): string
    {
        if (in_array(self::TAG_NOMBRE_DUPLICADO, $tags, true)) {
            return self::NOMBRE_DUPLICADO;
        }

        if (in_array(self::TAG_NOMBRES_SIMILARES, $tags, true)) {
            $similares = self::NOMBRES_SIMILARES;

            return $similares[intdiv($indice, 9) % count($similares)];
        }

        if (in_array(self::TAG_NOMBRE_DENSO, $tags, true)) {
            return self::APELLIDO_DENSO.', '.$this->rng->elegir(self::NOMBRES);
        }

        return $this->rng->elegir(self::APELLIDOS).', '.$this->rng->elegir(self::NOMBRES);
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function dniDe(int $indice, array $tags): string
    {
        if (in_array(self::TAG_PREFIJO_DNI, $tags, true)) {
            // Un único prefijo de 4 dígitos: `dni LIKE '3012%'` y
            // `dni LIKE '301200%'` devuelven >10 filas (2 páginas de paginate).
            $prefijo = self::PREFIJOS_DNI[0];

            return $prefijo.str_pad((string) (intdiv($indice, 7) % 10000), 4, '0', STR_PAD_LEFT);
        }

        // Paso de 91.237: ocho dígitos repartidos y sin repeticiones hasta el
        // índice 999. El perfil grande (500) queda dentro del rango de 8 dígitos.
        return (string) (10_000_000 + $indice * 91_237);
    }

    private function telefonoDe(int $indice): string
    {
        $base = '2614'.str_pad((string) $indice, 7, '0', STR_PAD_LEFT);

        // Formato internacional en uno de cada seis: la columna es varchar(20).
        return $indice % 6 === 5 ? '+54 '.$base : $base;
    }

    private function direccionDe(int $indice): string
    {
        $calle = self::CALLES[$indice % count(self::CALLES)];

        return $calle.' '.$this->rng->intEntre(1, 9999);
    }

    /**
     * @return array<int, string>|null
     */
    private function cooperativasDe(int $indice): ?array
    {
        if ($indice % 9 === 8) {
            return null;
        }

        $cantidad = $indice % 9 === 2 ? 0 : $this->rng->intEntre(1, 3);

        // El formulario de perfil envía etiquetas, no claves.
        return $this->rng->elegirN(array_values(User::COOPERATIVAS), $cantidad);
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function fechaDe(int $indice, array $tags): Carbon
    {
        if (in_array(self::TAG_ANTIGUO, $tags, true)) {
            return $this->referencia->copy()
                ->subMonths($this->rng->intEntre(9, 26))
                ->setTime($this->rng->intEntre(7, 19), $this->rng->intEntre(0, 59));
        }

        if (in_array(self::TAG_FRONTERA_30_DIAS, $tags, true)) {
            // La frontera exacta de 30 días depende del reloj; 29 y 31 dejan el
            // caso estable en cualquier corrida.
            return $this->referencia->copy()
                ->subDays($indice % 2 === 0 ? 29 : 31)
                ->setTime(10, 0);
        }

        $mesSaltado = (int) config('datos-prueba.mes_sin_registros', 2);
        $meses = array_values(array_filter(range(0, 5), fn (int $m) => $m !== $mesSaltado));
        $bucket = $meses[$indice % count($meses)];

        if ($bucket === 5) {
            return $this->referencia->copy()
                ->subDays($this->rng->intEntre(0, max(0, (int) $this->referencia->copy()->day - 1)))
                ->setTime($this->rng->intEntre(7, 19), $this->rng->intEntre(0, 59));
        }

        return $this->referencia->copy()
            ->subMonths(5 - $bucket)
            ->day($this->rng->intEntre(1, 28))
            ->setTime($this->rng->intEntre(7, 19), $this->rng->intEntre(0, 59));
    }

    /* ------------------------------------------------------------ propiedad */

    /**
     * @param  array<int, string>  $tags
     * @return array<int, array<string, mixed>>
     */
    private function componerPropiedades(
        int $indice,
        ?string $distritoDenso,
        array $tags,
        bool $primeraSinCultivos,
    ): array {
        $cantidad = in_array(self::TAG_MULTI_PROPIEDAD, $tags, true)
            ? 4
            : $this->rng->intEntre(1, 3);

        if ($primeraSinCultivos) {
            $cantidad = 2;
        }

        // El distrito libre se asigna a la ultima propiedad, asi que hace falta
        // al menos una propiedad anterior: con una sola, el distrito forzado
        // (grupo denso) se quedaria con la fila y el texto libre no apareceria.
        if (in_array(self::TAG_DISTRITO_LIBRE, $tags, true)) {
            $cantidad = max(2, $cantidad);
        }

        $reparto = $this->repartirCultivos($cantidad, $primeraSinCultivos);
        $distritos = array_keys(Propiedad::DISTRITOS);
        $propiedades = [];

        for ($j = 0; $j < $cantidad; $j++) {
            $cultivos = $this->componerCultivos($indice, $j, $reparto[$j], $tags);
            $hectareas = $this->hectareasDe($cultivos, $j, $tags);
            $rut = $this->rutDe($j, $tags);
            $tenencia = $this->tenenciaDe($tags);
            $derechoRiego = $this->derechoRiegoDe($tags);
            $malla = $this->rng->chance(0.45);

            $propiedades[] = [
                'cultivos' => $cultivos,
                'rut' => [
                    'calle' => self::CALLES[($indice + $j * 3) % count(self::CALLES)],
                    'numeracion' => (string) $this->rng->intEntre(1, 999999),
                    'distrito' => $this->distritoDe($indice, $j, $cantidad, $distritos, $distritoDenso, $tags),
                    'hectareas' => $hectareas,
                    'derecho_riego' => $derechoRiego,
                    'tipo_derecho_riego' => $derechoRiego
                        ? $this->rng->elegir(array_keys(Propiedad::TIPO_DERECHO_RIEGO))
                        : null,
                    'rut' => $rut['rut'],
                    'rut_valor' => $rut['rut_valor'],
                    'malla' => $malla,
                    // El 30% de las propiedades con malla deja el valor en NULL
                    // para ejercitar el fallback '0.00' del export XLSX.
                    'hectareas_malla' => ! $malla || $this->rng->chance(0.30)
                        ? null
                        : round($hectareas * $this->rng->decimalEntre(0.10, 0.80, 2), 2),
                    'cierre_perimetral' => $this->rng->chance(0.30),
                    'tipo_tenencia' => $tenencia,
                    'especificar_tenencia' => $tenencia === 'otros'
                        ? self::TENENCIAS_OTRAS[intdiv($indice, 8) % count(self::TENENCIAS_OTRAS)]
                        : null,
                    'lat' => $this->rng->decimalEntre(
                        (float) config('datos-prueba.lat.0'),
                        (float) config('datos-prueba.lat.1'),
                        7
                    ),
                    'lng' => $this->rng->decimalEntre(
                        (float) config('datos-prueba.lng.0'),
                        (float) config('datos-prueba.lng.1'),
                        7
                    ),
                ],
            ];
        }

        return $propiedades;
    }

    /**
     * Garantiza >= 2 cultivos por usuario salvo en las cohortes que lo
     * necesitan rotas a propósito.
     *
     * @return array<int, int>
     */
    private function repartirCultivos(int $cantidadPropiedades, bool $primeraSinCultivos): array
    {
        if ($primeraSinCultivos) {
            // 0 cultivos en la primera propiedad, >= 2 en el resto del usuario.
            $reparto = array_fill(0, $cantidadPropiedades, 0);
            $reparto[1] = 2;

            return $reparto;
        }

        $total = max(2, $cantidadPropiedades + $this->rng->intEntre(0, 2));
        $reparto = array_fill(0, $cantidadPropiedades, intdiv($total, $cantidadPropiedades));
        $resto = $total % $cantidadPropiedades;

        for ($k = 0; $k < $resto; $k++) {
            $reparto[$k]++;
        }

        return $reparto;
    }

    /**
     * @param  array<int, string>  $distritos
     * @param  array<int, string>  $tags
     */
    private function distritoDe(
        int $indice,
        int $j,
        int $cantidad,
        array $distritos,
        ?string $distritoDenso,
        array $tags,
    ): string {
        if ($j === 0 && $distritoDenso !== null) {
            return $distritoDenso;
        }

        if ($j === $cantidad - 1 && in_array(self::TAG_DISTRITO_LIBRE, $tags, true)) {
            return self::DISTRITOS_LIBRES[intdiv($indice, 25) % count(self::DISTRITOS_LIBRES)];
        }

        return $distritos[($indice + $j * 7) % count($distritos)];
    }

    /**
     * @param  array<int, string>  $tags
     * @return array{rut: bool, rut_valor: string|null}
     */
    private function rutDe(int $j, array $tags): array
    {
        // Las cohortes de RUT solo aplican a la primera propiedad del usuario.
        if ($j !== 0) {
            return $this->rutAleatorio();
        }

        if (in_array(self::TAG_RUT_CEROS, $tags, true)) {
            // El PDF hace floor((float) $rut_valor): los ceros iniciales se
            // pierden al renderizar.
            return ['rut' => true, 'rut_valor' => '0'.str_pad((string) $this->rng->intEntre(1, 99999999), 8, '0', STR_PAD_LEFT)];
        }

        if (in_array(self::TAG_RUT, $tags, true)) {
            return ['rut' => true, 'rut_valor' => (string) $this->rng->intEntre(1_000_000, 99_999_999)];
        }

        if (in_array(self::TAG_RUT_HUERFANO, $tags, true)) {
            // Dato huérfano: el filtro `rut` debe excluirlo igual.
            return ['rut' => false, 'rut_valor' => (string) $this->rng->intEntre(1_000_000, 99_999_999)];
        }

        return $this->rutAleatorio();
    }

    /**
     * @return array{rut: bool, rut_valor: string|null}
     */
    private function rutAleatorio(int $probabilidad = 15): array
    {
        return $this->rng->chance($probabilidad / 100)
            ? ['rut' => true, 'rut_valor' => (string) $this->rng->intEntre(1_000_000, 99_999_999)]
            : ['rut' => false, 'rut_valor' => null];
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function derechoRiegoDe(array $tags): bool
    {
        return ! in_array(self::TAG_SIN_DERECHO_RIEGO, $tags, true) && $this->rng->chance(0.75);
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function tenenciaDe(array $tags): string
    {
        if (in_array(self::TAG_TENENCIA_OTROS, $tags, true)) {
            return 'otros';
        }

        if (in_array(self::TAG_TENENCIA_ARRENDATARIO, $tags, true)) {
            return 'arrendatario';
        }

        return $this->rng->chance(0.75) ? 'propietario' : 'arrendatario';
    }

    /**
     * `hectareas` SIEMPRE se deriva de la suma de los cultivos + holgura, así
     * que `hectareas_disponibles` nunca queda negativo (C1).
     *
     * @param  array<int, array<string, mixed>>  $cultivos
     * @param  array<int, string>  $tags
     */
    private function hectareasDe(array $cultivos, int $j, array $tags): float
    {
        if ($cultivos === []) {
            return $this->rng->decimalEntre(3, 40, 2);
        }

        $suma = 0.0;

        foreach ($cultivos as $cultivo) {
            $suma += (float) $cultivo['hectareas'];
        }

        if ($j === 0 && in_array(self::TAG_CULTIVO_EXACTO, $tags, true)) {
            // `hectareas_disponibles == 0`: es el `max` dinámico que
            // `StoreCultivoRequest` inyecta en la regla de `hectareas`.
            return round($suma, 2);
        }

        return round($suma + $this->rng->decimalEntre(0.5, 8, 2), 2);
    }

    /* -------------------------------------------------------------- cultivo */

    /**
     * @param  array<int, string>  $tags
     * @return array<int, array<string, mixed>>
     */
    private function componerCultivos(int $indice, int $j, int $cantidad, array $tags): array
    {
        if ($cantidad === 0) {
            return [];
        }

        $cultivos = [];

        for ($k = 0; $k < $cantidad; $k++) {
            $primero = $j === 0 && $k === 0;
            $forzada = $primero ? $this->tipoForzado($tags) : null;
            $pareja = $forzada !== null
                ? $this->variedadDe($forzada, $tags)
                : $this->siguienteVariedad();

            $cultivos[] = [
                'tipo' => $pareja['tipo'],
                'variedad' => $pareja['variedad'],
                'estacion' => $this->rng->elegir(array_keys(Cultivo::ESTACIONES)),
                'hectareas' => $primero && in_array(self::TAG_CULTIVO_CERO, $tags, true)
                    ? 0.00
                    : $this->rng->decimalEntre(0.5, 12, 2),
                'manejo_cultivo' => in_array(self::TAG_ORGANICO, $tags, true)
                    ? 'Organico'
                    : $this->rng->elegir(array_keys(Cultivo::MANEJO_OPTIONS)),
                'tecnologia_riego' => in_array(self::TAG_RIEGO_NULO, $tags, true)
                    ? null
                    : $this->rng->elegir(array_keys(Cultivo::TECNOLOGIA_RIEGO)),
            ];
        }

        return $cultivos;
    }

    /**
     * Tipo forzado por cohorte para el primer cultivo del usuario.
     *
     * @param  array<int, string>  $tags
     */
    private function tipoForzado(array $tags): ?string
    {
        return match (true) {
            in_array(self::TAG_VARIEDAD_TOMATE, $tags, true) => 'Hortícola',
            in_array(self::TAG_VARIEDAD_MALBEC, $tags, true) => 'Vitícola',
            in_array(self::TAG_TIPO_OLIVICOLA, $tags, true) => 'Olivícola',
            in_array(self::TAG_TIPO_FRUTICOLA, $tags, true) => 'Frutícola',
            default => null,
        };
    }

    /**
     * @param  array<int, string>  $tags
     * @return array{tipo: string, variedad: string}
     */
    private function variedadDe(string $tipo, array $tags): array
    {
        if ($tipo === 'Hortícola' && in_array(self::TAG_VARIEDAD_TOMATE, $tags, true)) {
            return ['tipo' => $tipo, 'variedad' => 'Tomate Perita'];
        }

        if ($tipo === 'Vitícola' && in_array(self::TAG_VARIEDAD_MALBEC, $tags, true)) {
            return ['tipo' => $tipo, 'variedad' => 'Malbec'];
        }

        return $this->siguienteVariedad();
    }

    /* ----------------------------------------------------------- maquinaria */

    /**
     * @param  array<int, array<string, mixed>>  $propiedades
     * @param  array<int, string>  $tags
     * @return array<int, array<string, mixed>>
     */
    private function componerMaquinarias(array $propiedades, array $tags): array
    {
        if (in_array(self::TAG_SIN_MAQUINARIA, $tags, true)) {
            return [];
        }

        $frontera = in_array(self::TAG_TRACTOR_FRONTERA, $tags, true);
        $minima = in_array(self::TAG_MAQUINARIA_MINIMA, $tags, true);
        $fronteraIndice = 0;
        $maquinarias = [];

        foreach ($propiedades as $j => $propiedad) {
            // Restricción C1: como máximo una maquinaria por propiedad.
            if (! $this->rng->chance(0.80)) {
                continue;
            }

            $tractor = ! $minima && ($frontera || $this->rng->chance(0.55));
            $modelo = null;

            if ($tractor) {
                if ($frontera) {
                    // Alterna los dos extremos de la regla
                    // `integer|min:1900|max:{date('Y')}`.
                    $modelo = $fronteraIndice % 2 === 0 ? 1900 : (int) date('Y');
                    $fronteraIndice++;
                } else {
                    $modelo = $this->rng->intEntre(1990, (int) date('Y'));
                }
            }

            $maquinarias[] = [
                'propiedad' => $j,
                'tractor' => $tractor,
                'modelo_tractor' => $modelo,
                'multiple' => ! $minima && $this->rng->chance(0.20),
                'flags' => $minima
                    ? $this->flagsVacios()
                    : $this->flagsDe(array_unique(array_column($propiedad['cultivos'], 'tipo'))),
            ];
        }

        return $maquinarias;
    }

    /**
     * @return array<string, bool>
     */
    private function flagsVacios(): array
    {
        return array_fill_keys(array_keys(Maquinaria::IMPLEMENTOS_LABELS), false);
    }

    /**
     * @param  array<int, string>  $tipos
     * @return array<string, bool>
     */
    private function flagsDe(array $tipos): array
    {
        $flags = [];

        foreach (array_keys(Maquinaria::IMPLEMENTOS_LABELS) as $clave) {
            $flags[$clave] = $this->rng->chance(0.20);
        }

        // Coherencia tipo de cultivo -> implemento mas probable.
        if (in_array('Vitícola', $tipos, true)) {
            $flags['cosechadora'] = true;
            $flags['enfardadora'] = true;
        }

        if (in_array('Hortícola', $tipos, true)) {
            $flags['desmalezadora'] = true;
            $flags['pulverizadora_tractor'] = true;
        }

        if (in_array('Olivícola', $tipos, true)) {
            $flags['retroexcavadora'] = true;
        }

        return $flags;
    }

    /* ------------------------------------------------------------- comercio */

    /**
     * @param  array<int, string>  $tags
     * @return array<string, mixed>|null
     */
    private function componerComercio(int $indice, array $tags): ?array
    {
        if (in_array(self::TAG_SIN_COMERCIO, $tags, true)) {
            return null;
        }

        $json = in_array(self::TAG_COMERCIO_JSON, $tags, true);
        $soloFinca = in_array(self::TAG_SO_FINCA, $tags, true);

        $mercados = $this->mercadosDe($indice, $json, $soloFinca);
        $cooperativas = $this->cooperativasComercioDe($indice, $json, $soloFinca);

        return [
            'infraestructura_empaque' => $this->rng->chance(0.35),
            // `ComercioController` rechaza la combinación sin ninguna opción.
            'vende_en_finca' => $soloFinca
                || empty($mercados)
                && empty($cooperativas)
                || $this->rng->chance(0.25),
            'mercados' => $mercados,
            'cooperativas' => $cooperativas,
        ];
    }

    /**
     * @return array<int, string>|null
     */
    private function mercadosDe(int $indice, bool $json, bool $soloFinca): ?array
    {
        $valores = array_values(Comercio::MERCADOS);

        if ($json) {
            return match (intdiv($indice, 11) % 4) {
                0 => null,
                1 => [],
                // Clave que el formulario rechazaría: el export la emite tal cual.
                2 => ['Mercado Inexistente'],
                default => $this->rng->elegirN($valores, $this->rng->intEntre(1, 6)),
            };
        }

        return $soloFinca ? [] : $this->rng->elegirN($valores, $this->rng->intEntre(1, 3));
    }

    /**
     * @return array<int, string>|null
     */
    private function cooperativasComercioDe(int $indice, bool $json, bool $soloFinca): ?array
    {
        $valores = array_values(Comercio::COOPERATIVAS);

        if ($json) {
            return match (intdiv($indice, 11) % 5) {
                0 => null,
                1 => [],
                2 => ['Coop. Inexistente'],
                // Etiqueta del vocabulario de `User::COOPERATIVAS` (sin punto):
                // el export cae al `?? $k` y la emite sin resolver.
                3 => ['Coop Maipú'],
                default => $this->rng->elegirN($valores, $this->rng->intEntre(1, 4)),
            };
        }

        if ($soloFinca) {
            return [];
        }

        return $indice % 6 === 4 ? [] : $this->rng->elegirN($valores, $this->rng->intEntre(1, 3));
    }

    /* --------------------------------------------------------------- staff */

    /**
     * Los emails comparten dominio (`@rupal.test`) para que el filtro
     * `email` de `StaffUserController` tenga un token con >10 coincidencias.
     *
     * @return array<int, array<string, mixed>>
     */
    private function componerStaff(int $total): array
    {
        $administradores = max(2, (int) ceil($total / 3));
        $apellidos = ['Ruiz', 'Peralta', 'Sotelo', 'Larrañaga', 'Ocampo', 'Bravo', 'Molina', 'Quiroga'];
        $staff = [];

        for ($i = 0; $i < $total; $i++) {
            $staff[] = [
                'name' => $apellidos[$i % count($apellidos)].', '.$apellidos[($i + 3) % count($apellidos)],
                'email' => sprintf('%s%02d@rupal.test', $i < $administradores ? 'admin' : 'auditor', $i),
                'role' => $i < $administradores ? 'admin' : 'auditor',
                'active' => $i % 4 !== 3,
                'last_login_at' => $i % 3 === 2
                    ? null
                    : $this->referencia->copy()
                        ->subDays($this->rng->intEntre(0, 90))
                        ->setTime($this->rng->intEntre(8, 18), 15),
                'last_access_at' => $this->referencia->copy()
                    ->subDays($this->rng->intEntre(0, 30))
                    ->setTime($this->rng->intEntre(8, 18), 45),
                'deleted_at' => $i === $total - 1 ? $this->referencia->copy()->subDays(5) : null,
            ];
        }

        return $staff;
    }
}
