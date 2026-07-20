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
        $data = parent::share($request);

        if ($request->user() instanceof \App\Models\User) {
            $user = $request->user();
            $user->loadCount(['propiedades', 'comercializacion']);
            $user->load(['propiedades' => fn ($q) => $q->withCount(['cultivos', 'maquinaria'])]);

            $data['auth'] = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'avatar_url' => $user->avatar_url,
                    'profile_completeness' => $user->profile_completeness,
                    'propiedades_completeness' => $user->propiedades_count > 0 ? 100 : 0,
                    'cultivos_completeness' => $user->propiedades->sum('cultivos_count') > 0 ? 100 : 0,
                    'maquinarias_completeness' => $user->propiedades->sum('maquinaria_count') > 0 ? 100 : 0,
                    'comercializacion_completeness' => $user->comercializacion_count > 0 ? 100 : 0,
                ],
            ];
        }

        return $data;
    }
}
