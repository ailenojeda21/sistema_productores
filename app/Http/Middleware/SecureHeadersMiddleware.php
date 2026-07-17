<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');
        $response->headers->set('Content-Security-Policy', $this->buildCsp());

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function buildCsp(): string
    {
        if (app()->environment('local')) {
            $viteHost = '127.0.0.1:5173';
            $viteHttp = "http://{$viteHost}";
            $viteWs   = "ws://{$viteHost}";
            return implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' {$viteHttp}",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com {$viteHttp}",
                "font-src 'self' https://fonts.gstatic.com",
                "img-src 'self' data: https://*.tile.openstreetmap.org https://server.arcgisonline.com",
                "connect-src 'self' https://*.tile.openstreetmap.org https://server.arcgisonline.com {$viteWs} {$viteHttp}",
                "worker-src 'self' blob:",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);
        }

        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https://*.tile.openstreetmap.org https://server.arcgisonline.com",
            "connect-src 'self' https://*.tile.openstreetmap.org https://server.arcgisonline.com",
            "worker-src 'self' blob:",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }
}
