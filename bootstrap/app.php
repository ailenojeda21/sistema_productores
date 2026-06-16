<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\EventServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->web(prepend: [
            \App\Http\Middleware\SecureHeadersMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'staff.role' => \App\Http\Middleware\StaffRoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $e, Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403 && $request->routeIs('verification.verify')) {
                return redirect()->route('verification.notice')
                    ->with('status', 'link-expired');
            }
        });

        $exceptions->render(function (AuthenticationException $e, Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }
        });

        $exceptions->render(function (HttpException $e, Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $status = $e->getStatusCode();
                $messages = [
                    401 => 'No autenticado.',
                    403 => 'No autorizado.',
                    404 => 'Recurso no encontrado.',
                    419 => 'Sesión expirada. Intente nuevamente.',
                    429 => 'Demasiadas solicitudes. Intente más tarde.',
                ];

                return response()->json([
                    'message' => $messages[$status] ?? 'Error del servidor.',
                ], $status);
            }
        });

        $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
            if ($e instanceof ValidationException) {
                return;
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                $message = config('app.debug') ? $e->getMessage() : 'Error interno del servidor.';

                return response()->json(['message' => $message], 500);
            }
        });
    })->create();
