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

        return [
            'guard' => $guard,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
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
