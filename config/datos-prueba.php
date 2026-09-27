<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Perfil por defecto
    |--------------------------------------------------------------------------
    |
    | Perfil de dataset generado por `php artisan datos:prueba` y por
    | `DatosPruebaSeeder`. Se puede sobreescribir con DATOS_PRUEBA_PERFIL.
    |
    */

    'perfil' => env('DATOS_PRUEBA_PERFIL', 'mediano'),

    /*
    |--------------------------------------------------------------------------
    | Semilla del generador
    |--------------------------------------------------------------------------
    |
    | Determina el dataset completo: dos ejecuciones con la misma semilla
    | producen exactamente las mismas filas. No usar `fake()->unique()`
    | porque revienta al re-ejecutar el seeder.
    |
    */

    'semilla' => (int) env('DATOS_PRUEBA_SEMILLA', 20260905),

    /*
    |--------------------------------------------------------------------------
    | Password de las cuentas generadas
    |--------------------------------------------------------------------------
    |
    | Debe cumplir `Password::defaults()`: min 8, mixedCase, letters,
    | numbers, symbols.
    |
    */

    'password' => env('DATOS_PRUEBA_PASSWORD', 'DatosPrueba2026!'),

    /*
    |--------------------------------------------------------------------------
    | Referencia temporal
    |--------------------------------------------------------------------------
    |
    | Instante contra el que se calculan todas las fechas del dataset. Por
    | defecto es "ahora", porque las ventanas del dashboard son relativas.
    |
    | Fijarla (DATOS_PRUEBA_REFERENCIA=2026-09-26 12:00:00) hace que el dataset
    | sea reproducible byte a byte entre ejecuciones, que es la forma de
    | verificar el criterio C3 (determinismo).
    |
    */

    'referencia' => env('DATOS_PRUEBA_REFERENCIA'),

    /*
    |--------------------------------------------------------------------------
    | Perfiles disponibles
    |--------------------------------------------------------------------------
    |
    | `usuarios` = productores a generar. `staff` = usuarios staff.
    | `chunk` = tamaño de lote de los INSERT masivos (cultivos, maquinarias,
    | comercios).
    |
    | - pequeno : smoke test. Los grupos densos quedan en 3 usuarios, por lo
    |             que NO se puede verificar la paginación (>10 filas).
    | - mediano : dataset de referencia. Cada grupo denso tiene 11 usuarios
    |             (2 páginas de `paginate(10)`).
    | - grande  : 500 productores, para medir el export XLSX y el dashboard.
    |
    */

    'perfiles' => [
        'pequeno' => [
            'usuarios' => 30,
            'staff' => 8,
            'chunk' => 250,
        ],
        'mediano' => [
            'usuarios' => 100,
            'staff' => 12,
            'chunk' => 500,
        ],
        'grande' => [
            'usuarios' => 500,
            'staff' => 40,
            'chunk' => 1000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mes calendario sin registros (para el chart del dashboard)
    |--------------------------------------------------------------------------
    |
    | `StaffDashboardController::getUsuariosNuevosPorMes()` siempre devuelve
    | 6 buckets y rellena con `?? 0` los meses sin filas. Si la ventana es
    | [mes actual - 5 ... mes actual], el bucket 2 se deja vacío a propósito
    | para ejercitar esa rama.
    |
    */

    'mes_sin_registros' => 2,

    /*
    |--------------------------------------------------------------------------
    | Hectáreas de referencia del departamento (Lavalle)
    |--------------------------------------------------------------------------
    |
    | `StaffDashboardController::HECTAREAS_TOTAL_LAVALLE`. El dataset mantiene
    | el total cultivado muy por debajo para no disparar el `min(100, ...)`.
    |
    */

    'hectareas_departamento' => 10242,

    /*
    |--------------------------------------------------------------------------
    | Dominio geográfico de referencia (Lavalle / Luján de Cuyo)
    |--------------------------------------------------------------------------
    */

    'lat' => [-33.1000, -32.5500],

    'lng' => [-69.0000, -68.6000],

];
