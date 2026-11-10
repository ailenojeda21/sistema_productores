<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

test('login exitoso no registra el email en claro en el log', function () {
    Log::spy();

    User::factory()->create([
        'email' => 'pii-login@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $this->post('/login', [
        'email' => 'pii-login@example.com',
        'password' => 'secret123',
    ])->assertRedirect();

    Log::shouldHaveReceived('info')
        ->with('LOGIN_SUCCESS', Mockery::on(function (array $context) {
            return ! array_key_exists('user_email', $context)
                && ! in_array('pii-login@example.com', $context, true);
        }))
        ->once();
});

test('login fallido registra email_hash y no el email en claro', function () {
    Log::spy();

    $email = 'no-existe-pii@example.com';
    $expectedHash = substr(
        hash_hmac('sha256', strtolower($email), (string) config('app.key')),
        0,
        12
    );

    $this->post('/login', [
        'email' => $email,
        'password' => 'secret123',
    ])->assertSessionHasErrors();

    Log::shouldHaveReceived('warning')
        ->with('LOGIN_FAILED', Mockery::on(function (array $context) use ($email, $expectedHash) {
            return ! array_key_exists('user_email', $context)
                && ! array_key_exists('credentials', $context)
                && ! array_key_exists('password', $context)
                && ! in_array($email, $context, true)
                && ! in_array('secret123', $context, true)
                && ($context['email_hash'] ?? null) === $expectedHash;
        }))
        ->once();
});
