<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'email_verified_at' => $request->user()->email_verified_at,
                    'avatar_url' => $request->user()->avatar_url,
                    'profile_completeness' => $request->user()->profile_completeness,
                    'propiedades_completeness' => $request->user()->propiedades_completeness,
                    'cultivos_completeness' => $request->user()->cultivos_completeness,
                    'maquinarias_completeness' => $request->user()->maquinarias_completeness,
                    'comercializacion_completeness' => $request->user()->comercializacion_completeness,
                ] : null,
            ],
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
            ],
        ];
    }
}
