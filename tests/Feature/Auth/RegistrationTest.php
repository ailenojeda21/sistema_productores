<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'TestP@ss1',
        'password_confirmation' => 'TestP@ss1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
});

test('registro simultaneo del mismo email devuelve error de validacion y no 500', function () {
    $fired = false;

    User::creating(function () use (&$fired) {
        if ($fired) {
            return;
        }
        $fired = true;

        User::withoutEvents(fn () => User::factory()->create([
            'email' => 'carrera@test.com',
        ]));
    });

    $response = $this->post('/register', [
        'name' => 'Corredor',
        'email' => 'carrera@test.com',
        'password' => 'Secreta1!',
        'password_confirmation' => 'Secreta1!',
    ]);

    expect($response->status())->toBe(302)
        ->and($response->exception)->toBeInstanceOf(ValidationException::class);

    $response->assertSessionHasErrors(['email']);

    User::flushEventListeners();
    User::clearBootedModels();
});

test('un productor puede re-registrarse con su email despues de eliminar su cuenta', function () {
    $user = User::factory()->create([
        'email' => 'reingreso@test.com',
        'password' => Hash::make('ClaveActual123'),
    ]);

    $this->actingAs($user)
        ->delete(route('profile.destroy'), ['password' => 'ClaveActual123'])
        ->assertRedirect('/');

    $this->assertSoftDeleted($user);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'deleted-'.$user->id.'@removed.invalid',
    ]);

    $response = $this->post('/register', [
        'name' => 'Productor Reincidente',
        'email' => 'reingreso@test.com',
        'password' => 'TestP@ss1',
        'password_confirmation' => 'TestP@ss1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
});

test('el soft delete anonimiza los datos personales del productor', function () {
    $user = User::factory()->create([
        'name' => 'Juan Perez',
        'email' => 'juan.perez@test.com',
        'dni' => '26111222',
        'telefono' => '2615551234',
        'direccion' => 'Calle Falsa 123',
        'avatar' => 'dos.png',
        'cooperativas' => ['cooperativa_tulumaya'],
    ]);

    $userId = $user->id;
    $user->delete();
    $user->refresh();

    expect($user->trashed())->toBeTrue()
        ->and($user->name)->toBe('Usuario eliminado')
        ->and($user->email)->toBe("deleted-{$userId}@removed.invalid")
        ->and($user->dni)->toBe('')
        ->and($user->telefono)->toBe('')
        ->and($user->direccion)->toBe('')
        ->and($user->avatar)->toBeNull()
        ->and($user->cooperativas)->toBeNull();
});

test('eliminar la cuenta limpia los tokens de reset pendientes', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'tokens@test.com',
        'password' => Hash::make('ClaveActual123'),
    ]);
    Password::broker('users')->getRepository()->create($user);

    $this->assertDatabaseHas('password_reset_tokens', ['email' => 'tokens@test.com']);

    $this->actingAs($user)
        ->delete(route('profile.destroy'), ['password' => 'ClaveActual123'])
        ->assertRedirect('/');

    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'tokens@test.com']);
});
