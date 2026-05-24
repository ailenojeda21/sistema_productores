<?php

use App\Models\StaffUser;
use App\Models\User;

// Para tests del staff necesitamos autenticación de staff,
// no la autenticación regular de usuarios.

beforeEach(function () {
    // Usar la guard 'staff' para todos los tests de este archivo
});

test('muestra formulario de login del staff', function () {
    $response = $this->get('/staff/login');

    $response->assertOk();
});

test('staff puede iniciar sesión con credenciales correctas', function () {
    $staff = StaffUser::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/staff/login', [
        'email' => $staff->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/staff/dashboard');
    $this->assertAuthenticated('staff');
});

test('staff no puede iniciar sesión con contraseña incorrecta', function () {
    $staff = StaffUser::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/staff/login', [
        'email' => $staff->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest('staff');
});

test('usuario regular no puede acceder al dashboard del staff', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/staff/dashboard');

    $response->assertRedirect('/login');
});

test('staff autenticado puede acceder al dashboard', function () {
    $staff = StaffUser::factory()->create();

    $response = $this->actingAs($staff, 'staff')->get('/staff/dashboard');

    $response->assertOk();
});

test('staff puede cerrar sesión', function () {
    $staff = StaffUser::factory()->create();

    $response = $this->actingAs($staff, 'staff')->post('/staff/logout');

    $response->assertRedirect('/staff/login');
    $this->assertGuest('staff');
});
