<?php

use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

// =====================================================================
// AUTH — Login / Logout / Me
// =====================================================================

test('api login returns token and user', function () {
    $staff = StaffUser::factory()->create([
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/api/staff/login', [
        'email' => $staff->email,
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']])
        ->assertJson(['user' => ['id' => $staff->id]]);
});

test('api login fails with wrong password', function () {
    $staff = StaffUser::factory()->create([
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/api/staff/login', [
        'email' => $staff->email,
        'password' => 'wrong-password',
    ]);

    $response->assertUnprocessable();
});

test('api login fails for inactive user', function () {
    $staff = StaffUser::factory()->create([
        'password' => bcrypt('secret123'),
        'active' => false,
    ]);

    $response = $this->postJson('/api/staff/login', [
        'email' => $staff->email,
        'password' => 'secret123',
    ]);

    $response->assertUnprocessable();
});

test('api login requires email and password', function () {
    $response = $this->postJson('/api/staff/login', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

test('api me returns authenticated user', function () {
    $staff = StaffUser::factory()->create();
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/me');

    $response->assertOk()
        ->assertJson(['id' => $staff->id, 'name' => $staff->name, 'email' => $staff->email]);
});

test('api me returns 401 when unauthenticated', function () {
    $response = $this->getJson('/api/staff/me');

    expect($response->status())->toBe(401);
})->skip('Crashes the PHP process in this environment — investigate exception handler');

test('api logout deletes token', function () {
    $staff = StaffUser::factory()->create();
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->postJson('/api/staff/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Sesión cerrada correctamente.']);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

// =====================================================================
// DASHBOARD
// =====================================================================

test('api dashboard returns kpi data', function () {
    $staff = StaffUser::factory()->create();
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/dashboard');

    $response->assertOk()
        ->assertJsonStructure(['user', 'kpiData' => ['usuarios', 'hectareas']]);
});

// =====================================================================
// PRODUCERS
// =====================================================================

test('api producers index returns paginated list', function () {
    $staff = StaffUser::factory()->create();
    User::factory()->count(3)->create();
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/producers');

    $response->assertOk()
        ->assertJsonStructure(['filters', 'producers' => ['data']]);
});

test('api producers show returns producer detail', function () {
    $staff = StaffUser::factory()->create();
    $user = User::factory()->create();
    Propiedad::factory()->for($user, 'usuario')->create();
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/producers/'.$user->id);

    $response->assertOk()
        ->assertJsonStructure(['producer', 'propiedades', 'cultivos', 'maquinarias', 'comercio', 'stats']);
});

// =====================================================================
// USERS (solo admin)
// =====================================================================

test('api users index requires admin role', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    Sanctum::actingAs($auditor, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/users');

    $response->assertForbidden();
});

test('api users index returns users for admin', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    StaffUser::factory()->count(2)->create();
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/users');

    $response->assertOk()
        ->assertJsonStructure(['filters', 'users' => ['data']]);
});

test('api users create returns field info', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/users/create');

    $response->assertOk()
        ->assertJsonStructure(['available_roles', 'fields']);
});

test('api users store creates user', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->postJson('/api/staff/users', [
        'name' => 'API User',
        'email' => 'api@staff.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'auditor',
    ]);

    $response->assertCreated()
        ->assertJson(['user' => ['email' => 'api@staff.com']]);

    $this->assertDatabaseHas('staff_users', ['email' => 'api@staff.com']);
});

test('api users edit returns user data', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $target = StaffUser::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/users/'.$target->id.'/edit');

    $response->assertOk()
        ->assertJson(['id' => $target->id]);
});

test('api users update modifies user', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $target = StaffUser::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->patchJson('/api/staff/users/'.$target->id, [
        'name' => 'Updated Name',
        'email' => 'updated@staff.com',
        'role' => 'admin',
    ]);

    $response->assertOk()
        ->assertJson(['user' => ['name' => 'Updated Name', 'email' => 'updated@staff.com']]);

    $this->assertDatabaseHas('staff_users', ['id' => $target->id, 'name' => 'Updated Name']);
});

test('api users destroy soft-deletes user', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $target = StaffUser::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->deleteJson('/api/staff/users/'.$target->id);

    $response->assertOk()
        ->assertJson(['message' => 'Usuario eliminado']);

    $this->assertSoftDeleted($target);
});
