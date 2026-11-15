<?php

namespace App\Support;

class CorsOrigins
{
    /**
     * Construye la lista de origenes permitidos para CORS.
     *
     * Toma APP_URL como base, le suma los dominios de CORS_ALLOWED_ORIGINS
     * (separados por coma) y descarta vacios, duplicados y el comodin `*`:
     * la API no se abre a cualquier sitio.
     *
     * @return array<int, string>
     */
    public static function allowed(?string $appUrl, ?string $extraOrigins): array
    {
        $candidates = array_merge(
            [(string) $appUrl],
            explode(',', (string) $extraOrigins)
        );

        $origins = [];

        foreach ($candidates as $origin) {
            $origin = rtrim(trim($origin), '/');

            if ($origin === '' || $origin === '*') {
                continue;
            }

            $origins[$origin] = $origin;
        }

        return array_values($origins);
    }
}
