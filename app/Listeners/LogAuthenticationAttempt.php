<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogAuthenticationAttempt
{
    private function context($event): array
    {
        $guard = match (true) {
            $event instanceof Login || $event instanceof Logout => property_exists($event, 'guard') ? $event->guard : 'web',
            $event instanceof Failed => property_exists($event, 'guard') ? $event->guard : 'web',
            default => 'unknown',
        };

        $user = match (true) {
            $event instanceof Login || $event instanceof Logout => $event->user,
            $event instanceof Failed => $event->user,
            default => null,
        };

        $context = [
            'guard' => $guard,
            'user_id' => $user?->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Sin usuario no hay identificador que registrar. Se usa un hash
        // truncado del email para poder correlacionar intentos sin
        // almacenar el dato personal en claro.
        if ($event instanceof Failed && $user === null) {
            $email = strtolower((string) ($event->credentials['email'] ?? ''));

            if ($email !== '') {
                $context['email_hash'] = substr(
                    hash_hmac('sha256', $email, (string) config('app.key')),
                    0,
                    12
                );
            }
        }

        return $context;
    }

    public function handle($event): void
    {
        $ctx = $this->context($event);

        match (true) {
            $event instanceof Login => Log::info('LOGIN_SUCCESS', $ctx),
            $event instanceof Logout => Log::info('LOGOUT', $ctx),
            $event instanceof Failed => Log::warning('LOGIN_FAILED', $ctx),
            default => null,
        };
    }
}
