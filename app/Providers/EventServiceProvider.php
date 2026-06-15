<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationAttempt;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogAuthenticationAttempt::class,
        ],
        Failed::class => [
            LogAuthenticationAttempt::class,
        ],
        Logout::class => [
            LogAuthenticationAttempt::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
