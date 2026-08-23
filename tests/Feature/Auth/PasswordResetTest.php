<?php

use App\Models\User;
use App\Notifications\UserResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, UserResetPassword::class);
});

test('forgot password responde identico si el email existe o no', function () {
    Notification::fake();

    $user = User::factory()->create();

    $existente = $this->post('/forgot-password', ['email' => $user->email]);
    $inexistente = $this->post('/forgot-password', ['email' => 'nadie@example.com']);

    $existente->assertRedirect();
    $inexistente
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect($inexistente->getSession()->get('status'))
        ->toBe($existente->getSession()->get('status'))
        ->toBe('Si el correo esta registrado, te enviaremos un enlace para restablecer tu contrasena.');

    Notification::assertSentTo($user, UserResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, UserResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, UserResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'NewP@ss123',
            'password_confirmation' => 'NewP@ss123',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});
