<?php

use App\Models\StaffUser;
use Illuminate\Validation\ValidationException;

test('admin puede ver listado de usuarios staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->get(route('staff.users.index'));

    $response->assertOk();
});

test('auditor no puede ver listado de usuarios staff', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);

    $response = $this->actingAs($auditor, 'staff')
        ->get(route('staff.users.index'));

    $response->assertForbidden();
});

test('admin puede ver formulario de creacion de usuario staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->get(route('staff.users.create'));

    $response->assertOk();
});

test('admin puede crear usuario staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->post(route('staff.users.store'), [
            'name' => 'Nuevo Staff',
            'email' => 'nuevo@staff.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'auditor',
        ]);

    $response->assertRedirect(route('staff.dashboard'));
    $this->assertDatabaseHas('staff_users', [
        'email' => 'nuevo@staff.com',
        'role' => 'auditor',
    ]);
});

test('politica de contrasena rechaza claves debiles en creacion de staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->post(route('staff.users.store'), [
            'name' => 'Debil',
            'email' => 'debil@staff.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => 'auditor',
        ]);

    $response->assertSessionHasErrors('password');
    $this->assertDatabaseMissing('staff_users', ['email' => 'debil@staff.com']);
});

test('actualizacion acepta contrasena que cumple la politica global', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();

    $response = $this->actingAs($admin, 'staff')
        ->patch(route('staff.users.update', $staffUser->id), [
            'name' => 'Con Clave Nueva',
            'email' => $staffUser->email,
            'role' => $staffUser->role,
            'password' => 'Password456!',
            'password_confirmation' => 'Password456!',
        ]);

    $response->assertRedirect(route('staff.users.index'));
    $this->assertDatabaseHas('staff_users', ['id' => $staffUser->id, 'name' => 'Con Clave Nueva']);
});

test('actualizacion rechaza contrasena debil', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();

    $response = $this->actingAs($admin, 'staff')
        ->patch(route('staff.users.update', $staffUser->id), [
            'name' => 'Sin Cambio Real',
            'email' => $staffUser->email,
            'role' => $staffUser->role,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

    $response->assertSessionHasErrors('password');
});

test('registro simultaneo de email staff devuelve error de validacion y no 500', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $fired = false;
    StaffUser::creating(function () use (&$fired) {
        if ($fired) {
            return;
        }
        $fired = true;

        StaffUser::withoutEvents(fn () => StaffUser::factory()->create([
            'email' => 'carrera@staff.com',
        ]));
    });

    $response = $this->actingAs($admin, 'staff')
        ->post(route('staff.users.store'), [
            'name' => 'Corredor Staff',
            'email' => 'carrera@staff.com',
            'password' => 'Password789!',
            'password_confirmation' => 'Password789!',
            'role' => 'auditor',
        ]);

    expect($response->status())->toBe(302)
        ->and($response->exception)->toBeInstanceOf(ValidationException::class);

    $response->assertSessionHasErrors(['email']);

    StaffUser::flushEventListeners();
    StaffUser::clearBootedModels();
});

test('admin puede ver formulario de edicion de usuario staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();

    $response = $this->actingAs($admin, 'staff')
        ->get(route('staff.users.edit', $staffUser->id));

    $response->assertOk();
});

test('admin puede actualizar usuario staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();

    $response = $this->actingAs($admin, 'staff')
        ->patch(route('staff.users.update', $staffUser->id), [
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@staff.com',
            'role' => 'admin',
        ]);

    $response->assertRedirect(route('staff.users.index'));
    $this->assertDatabaseHas('staff_users', [
        'id' => $staffUser->id,
        'name' => 'Usuario Actualizado',
        'email' => 'actualizado@staff.com',
    ]);
});

test('admin puede eliminar (soft delete) usuario staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();

    $response = $this->actingAs($admin, 'staff')
        ->delete(route('staff.users.destroy', $staffUser->id));

    $response->assertSessionHas('success', 'Usuario eliminado');
    $this->assertSoftDeleted($staffUser);
});

test('desactivar staff revoca sus tokens', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();
    $staffUser->createToken('dev1');

    $response = $this->actingAs($admin, 'staff')
        ->patch(route('staff.users.update', $staffUser->id), [
            'active' => false,
        ]);

    $response->assertSessionHas('success', 'Estado actualizado');
    $this->assertDatabaseHas('staff_users', ['id' => $staffUser->id, 'active' => false]);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('eliminar staff revoca sus tokens', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $staffUser = StaffUser::factory()->create();
    $staffUser->createToken('dev1');

    $response = $this->actingAs($admin, 'staff')
        ->delete(route('staff.users.destroy', $staffUser->id));

    $response->assertSessionHas('success', 'Usuario eliminado');
    $this->assertSoftDeleted($staffUser);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('admin no puede eliminar su propio usuario', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->delete(route('staff.users.destroy', $admin->id));

    $response->assertSessionHas('error', 'No puedes eliminarte a ti mismo.');
    $this->assertNotSoftDeleted($admin);
});

test('admin no puede cambiar su propio rol', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin, 'staff')
        ->patch(route('staff.users.update', $admin->id), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'auditor',
        ]);

    $response->assertSessionHas('error', 'No puedes cambiar tu propio rol.');
    $this->assertDatabaseHas('staff_users', [
        'id' => $admin->id,
        'role' => 'admin',
    ]);
});
