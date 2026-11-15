<?php

/*
|--------------------------------------------------------------------------
| Proxies de confianza
|--------------------------------------------------------------------------
|
| Illuminate\Http\Middleware\TrustProxies lee esta clave en cada request
| (cuando bootstrap/app.php no fija TrustProxies::at()), asi que el valor
| viaja dentro de la config compilada y sigue funcionando con config:cache.
|
| TRUSTED_PROXIES acepta una lista separada por comas de IPs o rangos, por
| ejemplo `10.0.0.1,10.0.0.0/8`, o el comodin `*`.
|
| Sin valor (o vacio) no se confia en ningun proxy: los encabezados
| X-Forwarded-* se ignoran y la IP del cliente sale de REMOTE_ADDR.
|
| `*` confia en la IP que abre la conexion, cualquiera sea (asume que toda
| conexion viene de un proxy). Usarlo solo si la aplicacion es accesible
| unicamente a traves de ese proxy.
|
*/

$proxies = env('TRUSTED_PROXIES');

return [

    'proxies' => is_string($proxies) && trim($proxies) === '' ? null : $proxies,

];
