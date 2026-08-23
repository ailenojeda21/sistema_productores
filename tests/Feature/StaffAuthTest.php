<?php

use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

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

test('login devuelve el mismo mensaje para email inexistente, inactivo y contraseña incorrecta', function () {
    $inactive = StaffUser::factory()->create([
        'active' => false,
        'password' => bcrypt('password'),
    ]);

    $this->post('/staff/login', [
        'email' => 'no-existe@test.com',
        'password' => 'password',
    ])->assertSessionHasErrors(['email' => 'Credenciales incorrectas.']);

    $this->post('/staff/login', [
        'email' => $inactive->email,
        'password' => 'password',
    ])->assertSessionHasErrors(['email' => 'Credenciales incorrectas.']);

    $this->post('/staff/login', [
        'email' => $inactive->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors(['email' => 'Credenciales incorrectas.']);
});

test('staff admin bypasea gates staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    expect(Gate::forUser($admin)->allows('view-dashboard'))->toBeTrue();
});

test('productor con rol Spatie admin no bypasea gates staff', function () {
    $producer = User::factory()->create();
    Role::firstOrCreate(['name' => 'admin']);
    $producer->assignRole('admin');

    expect($producer->hasRole('admin'))->toBeTrue()
        ->and(Gate::forUser($producer)->allows('view-dashboard'))->toBeFalse()
        ->and(Gate::forUser($producer)->allows('export-producers'))->toBeFalse()
        ->and(Gate::forUser($producer)->allows('manage-staff'))->toBeFalse();
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

test('staff desactivado con sesion web activa es deslogueado y redirigido al login', function () {
    $staff = StaffUser::factory()->create();

    $this->actingAs($staff, 'staff')->get('/staff/dashboard')->assertOk();

    $staff->update(['active' => false]);

    $response = $this->actingAs($staff, 'staff')->get('/staff/dashboard');

    $response->assertRedirect(route('staff.login'));
    $response->assertSessionHas('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
    $this->assertGuest('staff');
});

test('las tablas de reset de staff y productores son independientes', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'compartido@test.com']);
    $staff = StaffUser::factory()->create(['email' => 'compartido@test.com']);

    $userToken = Password::broker('users')->getRepository()->create($user);
    $staffToken = Password::broker('staff_users')->getRepository()->create($staff);

    expect(DB::table('password_reset_tokens')->where('email', 'compartido@test.com')->exists())->toBeTrue()
        ->and(DB::table('staff_password_reset_tokens')->where('email', 'compartido@test.com')->exists())->toBeTrue()
        ->and(DB::table('password_reset_tokens')->count())->toBe(1)
        ->and(DB::table('staff_password_reset_tokens')->count())->toBe(1)
        ->and(Hash::check($userToken, DB::table('password_reset_tokens')->value('token')))->toBeTrue()
        ->and(Hash::check($staffToken, DB::table('staff_password_reset_tokens')->value('token')))->toBeTrue();
});

test('un token de productor no puede restablecer la contrasena de una cuenta staff', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'compartido2@test.com']);
    $staff = StaffUser::factory()->create([
        'email' => 'compartido2@test.com',
        'password' => bcrypt('ClaveOriginal123'),
    ]);

    $producerToken = Password::broker('users')->getRepository()->create($user);

    $this->post('/staff/reset-password', [
        'token' => $producerToken,
        'email' => 'compartido2@test.com',
        'password' => 'NuevaContrasena123',
        'password_confirmation' => 'NuevaContrasena123',
    ])->assertSessionHasErrors();

    expect(Hash::check('ClaveOriginal123', $staff->fresh()->password))->toBeTrue();
});

test('un token del broker staff si restablece la contrasena del staff', function () {
    Notification::fake();

    $staff = StaffUser::factory()->create([
        'email' => 'reset-ok@staff.com',
        'password' => bcrypt('ClaveOriginal123'),
    ]);

    $staffToken = Password::broker('staff_users')->getRepository()->create($staff);

    $response = $this->post('/staff/reset-password', [
        'token' => $staffToken,
        'email' => 'reset-ok@staff.com',
        'password' => 'NuevaContrasena123',
        'password_confirmation' => 'NuevaContrasena123',
    ]);

    $response->assertSessionHasNoErrors();
    expect(Hash::check('NuevaContrasena123', $staff->fresh()->password))->toBeTrue()
        ->and(Hash::check('ClaveOriginal123', $staff->fresh()->password))->toBeFalse();
});
