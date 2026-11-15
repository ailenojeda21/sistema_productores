<?php

use App\Models\StaffUser;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

/**
 * Matriz de roles staff (Decision A3 de AGENTS.md):
 *
 *  - Productores: Admin y Auditor tienen exactamente los mismos permisos
 *    (listado, detalle, filtros, exportacion XLSX de la lista y el informe
 *    PDF del detalle del productor).
 *  - Usuarios staff: solo el Admin crea, edita y elimina. Auditor recibe 403.
 *
 * El PDF del productor NO lo genera el backend: `Staff/Producers/Show.vue`
 * renderiza `#pdf-report` y lo imprime con `window.print()` en el navegador.
 * Lo unico verificable del lado del servidor es que ambos roles pueden abrir
 * el detalle que contiene el boton "Exportar PDF".
 */
function assertExportacionXlsx($response): void
{
    $response->assertOk();
    $response->assertHeader(
        'Content-Type',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    expect($response->headers->get('Content-Disposition'))->toContain('.xlsx');
}

// =====================================================================
// PRODUCTORES: ADMIN + AUDITOR
// =====================================================================

test('un auditor exporta la lista de productores en excel y abre el detalle del pdf via web', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $producer = User::factory()->create();

    $this->actingAs($auditor, 'staff');

    // Filtros Distrito/Variedad/Tipo/Manejo y "Exportar todos" -> Excel.
    assertExportacionXlsx($this->get(route('staff.producers.export')));
    assertExportacionXlsx($this->get(route('staff.producers.export-all')));

    // El detalle es la pantalla que imprime el PDF con window.print().
    $this->get(route('staff.producers.show', $producer->id))->assertOk();
});

test('un auditor exporta la lista de productores en excel y consulta el detalle via api', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $producer = User::factory()->create();

    Sanctum::actingAs($auditor, ['*'], 'staff-api');

    assertExportacionXlsx($this->getJson('/api/staff/producers/export'));

    $this->getJson('/api/staff/producers/'.$producer->id)->assertOk();
});

// =====================================================================
// USUARIOS STAFF: SOLO ADMIN
// =====================================================================

test('un auditor recibe 403 al crear editar y eliminar usuarios staff', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $target = StaffUser::factory()->create(['role' => 'auditor']);

    $nuevoUsuario = [
        'name' => 'Intruso Matriz',
        'email' => 'intruso-matriz@staff.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'auditor',
    ];

    $edicion = [
        'name' => 'Editado por auditor',
        'email' => 'editado-por-auditor@staff.com',
        'role' => 'admin',
    ];

    // Web
    $this->actingAs($auditor, 'staff');

    $this->post(route('staff.users.store'), $nuevoUsuario)->assertForbidden();
    $this->patch(route('staff.users.update', $target->id), $edicion)->assertForbidden();
    $this->delete(route('staff.users.destroy', $target->id))->assertForbidden();

    // API
    Sanctum::actingAs($auditor, ['*'], 'staff-api');

    $this->postJson('/api/staff/users', $nuevoUsuario)->assertForbidden();
    $this->patchJson('/api/staff/users/'.$target->id, $edicion)->assertForbidden();
    $this->deleteJson('/api/staff/users/'.$target->id)->assertForbidden();

    $this->assertDatabaseMissing('staff_users', ['email' => 'intruso-matriz@staff.com']);
    $this->assertDatabaseHas('staff_users', [
        'id' => $target->id,
        'name' => $target->name,
        'role' => 'auditor',
    ]);
    $this->assertNotSoftDeleted($target);
});

test('un admin exporta productores y gestiona usuarios staff', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    $target = StaffUser::factory()->create(['role' => 'auditor']);
    User::factory()->count(2)->create();

    // API: mismas rutas de productores que el auditor + CRUD de usuarios.
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    assertExportacionXlsx($this->getJson('/api/staff/producers/export'));

    $this->getJson('/api/staff/users')->assertOk();

    $this->postJson('/api/staff/users', [
        'name' => 'Creado por admin',
        'email' => 'creado-por-admin@staff.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'auditor',
    ])->assertCreated();

    $this->patchJson('/api/staff/users/'.$target->id, [
        'name' => 'Editado por admin',
        'email' => 'editado-por-admin@staff.com',
        'role' => 'auditor',
    ])->assertOk();

    $eliminado = StaffUser::factory()->create(['role' => 'auditor']);
    $this->deleteJson('/api/staff/users/'.$eliminado->id)->assertOk();

    // Web: exportaciones de productores.
    $this->actingAs($admin, 'staff');

    assertExportacionXlsx($this->get(route('staff.producers.export')));
    assertExportacionXlsx($this->get(route('staff.producers.export-all')));

    $this->get(route('staff.users.index'))->assertOk();

    $this->assertDatabaseHas('staff_users', ['email' => 'creado-por-admin@staff.com']);
    $this->assertDatabaseHas('staff_users', ['email' => 'editado-por-admin@staff.com']);
    $this->assertSoftDeleted($eliminado);
});
