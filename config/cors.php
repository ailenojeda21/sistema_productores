<?php

use App\Support\CorsOrigins;

/*
|--------------------------------------------------------------------------
| Cross-Origin Resource Sharing (CORS)
|--------------------------------------------------------------------------
|
| Que origenes pueden llamar a la API desde un navegador. La lista se arma
| con APP_URL mas los dominios extra de CORS_ALLOWED_ORIGINS (separados por
| coma), para no abrir la API a cualquier sitio.
|
| No se admite `*`: se descarta si aparece en cualquiera de las dos
| variables, y si la lista queda vacia ningun origen recibe encabezados CORS.
|
| Illuminate\Http\Middleware\HandleCors relee esta config en cada request, y
| env() se resuelve al compilarla con config:cache, asi que el valor no se
| pierde en produccion.
|
*/

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => CorsOrigins::allowed(
        env('APP_URL'),
        env('CORS_ALLOWED_ORIGINS')
    ),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
