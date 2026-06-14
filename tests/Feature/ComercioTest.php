<?php

use App\Models\User;
use App\Models\Comercio;

test('user puede ver listado de comercios', function () {
    $user = User::factory()->create();
    Comercio::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->get('/comercios');

    $response->assertOk();
});

test('user puede crear comercio', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/comercios', [
        'infraestructura_empaque' => true,
        'vende_en_finca' => false,
        'mercados' => ['Mercado local'],
        'cooperativas' => [],
    ]);

    $response->assertRedirect('/comercios');
    $this->assertDatabaseHas('comercios', [
        'usuario_id' => $user->id,
    ]);
});

test('user puede ver formulario de edicion de comercio', function () {
    $user = User::factory()->create();
    $comercio = Comercio::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->get("/comercios/{$comercio->id}/edit");

    $response->assertOk();
});

test('user puede actualizar comercio', function () {
    $user = User::factory()->create();
    $comercio = Comercio::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->put("/comercios/{$comercio->id}", [
        'infraestructura_empaque' => false,
        'vende_en_finca' => true,
        'mercados' => ['Exportación'],
        'cooperativas' => [],
    ]);

    $response->assertRedirect('/comercios');
});

test('user puede eliminar comercio', function () {
    $user = User::factory()->create();
    $comercio = Comercio::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->delete("/comercios/{$comercio->id}");

    $response->assertRedirect('/comercios');
    $this->assertDatabaseMissing('comercios', ['id' => $comercio->id]);
});

test('usuario no puede editar comercio de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $comercio = Comercio::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->get("/comercios/{$comercio->id}/edit");

    $response->assertForbidden();
});

test('usuario no puede actualizar comercio de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $comercio = Comercio::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->put("/comercios/{$comercio->id}", [
            'infraestructura_empaque' => true,
            'vende_en_finca' => true,
            'mercados' => [],
            'cooperativas' => [],
        ]);

    $response->assertForbidden();
});

test('usuario no puede eliminar comercio de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $comercio = Comercio::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->delete("/comercios/{$comercio->id}");

    $response->assertForbidden();
});

test('invitado no puede ver listado de comercios', function () {
    $response = $this->get('/comercios');

    $response->assertRedirect('/login');
});

test('invitado no puede crear comercio', function () {
    $response = $this->post('/comercios', [
        'infraestructura_empaque' => true,
        'vende_en_finca' => true,
    ]);

    $response->assertRedirect('/login');
});

test('comercio requiere al menos una opcion de comercializacion', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/comercios', [
        'infraestructura_empaque' => true,
    ]);

    $response->assertSessionHasErrors(['comercializacion']);
});
