<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | web        → Productores (modelo User)           → login, sesión web
    | staff      → Staff (modelo StaffUser)             → sesión web staff
    | staff-api  → Staff (modelo StaffUser)             → API con Sanctum tokens
    |              Requiere HasApiTokens en StaffUser (ya agregado).
    |
    */

    'guards' => [

        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff_users',
        ],

        'staff-api' => [
            'driver' => 'sanctum',
            'provider' => 'staff_users',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        'staff_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\StaffUser::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [

        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
