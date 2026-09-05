<?php

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $originalEmail = $user->email;

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'dni' => '12345678',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame($originalEmail, $user->email);
    $this->assertNotNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'dni' => '12345678',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertSoftDeleted($user);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('cooperativas fuera del catalogo son rechazadas', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'dni' => '12345678',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
            'tiene_cooperativas' => '1',
            'cooperativas' => ['Coop Inventada'],
        ]);

    $response->assertSessionHasErrors(['cooperativas.0']);
});

test('cooperativas del catalogo se aceptan', function () {
    $user = User::factory()->create();
    $coop = array_values(User::COOPERATIVAS)[0];

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'dni' => '12345678',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
            'tiene_cooperativas' => '1',
            'cooperativas' => [$coop],
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh()->cooperativas)->toBe([$coop]);
});

test('un productor no puede usar el DNI de otro productor activo', function () {
    $otro = User::factory()->create(['dni' => '99887766']);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->patch('/profile', [
            'name' => $user->name,
            'dni' => '99887766',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

    $response
        ->assertSessionHasErrors('dni')
        ->assertRedirect('/profile');

    expect($user->refresh()->dni)->not->toBe('99887766');
});

test('un productor puede conservar su propio DNI', function () {
    $user = User::factory()->create(['dni' => '11223344']);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'dni' => '11223344',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh()->dni)->toBe('11223344');
});

test('el DNI de un productor eliminado no bloquea a un nuevo productor', function () {
    $borrado = User::factory()->create(['dni' => '55443322']);
    $borrado->delete();

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'dni' => '55443322',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh()->dni)->toBe('55443322');
});

test('el DNI vacio no bloquea a otros productores', function () {
    User::factory()->create(['dni' => '']);
    $user = User::factory()->create(['dni' => '']);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'dni' => '11223344',
            'telefono' => '1123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user->refresh()->dni)->toBe('11223344');
});

test('el indice de la BD rechaza dos DNI iguales incluso eludir la validacion', function () {
    $user = User::factory()->create(['dni' => '33445566']);

    // Insertar directamente un DNI duplicado elude la validacion de la app;
    // el indice unico de la BD debe rechazarlo.
    expect(fn () => DB::table('users')->insert([
        'name' => 'Fantasma',
        'email' => 'fantasma@x.com',
        'password' => bcrypt('password'),
        'dni' => $user->dni,
        'telefono' => '',
        'direccion' => '',
    ]))->toThrow(UniqueConstraintViolationException::class);
});
