<?php

use App\Models\StaffUser;

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
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'auditor',
        ]);

    $response->assertRedirect(route('staff.dashboard'));
    $this->assertDatabaseHas('staff_users', [
        'email' => 'nuevo@staff.com',
        'role' => 'auditor',
    ]);
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
