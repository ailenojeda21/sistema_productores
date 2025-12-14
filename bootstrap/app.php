<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejar TokenMismatchException (error 419 - CSRF)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Si es una petición AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, recarga la página.',
                    'redirect' => route('login')
                ], 419);
            }
            
            // Si es el formulario de login
            if ($request->is('login') && $request->isMethod('post')) {
                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            }
            
            // Para otras rutas POST/PUT/PATCH/DELETE
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                return redirect()->back()
                    ->withInput($request->except(['_token', '_method', 'password', 'password_confirmation']))
                    ->with('error', 'Tu sesión ha expirado. Por favor, intenta nuevamente.');
            }
            
            // Para navegación normal (GET), redirigir a login si no está autenticado
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            }
            
            // Si está autenticado, recargar la página actual
            return redirect()->refresh()
                ->with('info', 'La página ha sido recargada.');
        });
    })->create();
