<?php

use App\Models\User;
use App\Models\Propiedad;
use App\Models\Maquinaria;

test('user puede ver listado de maquinaria', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Maquinaria::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->get('/maquinaria');

    $response->assertOk();
});

test('user puede crear maquinaria', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->post('/maquinaria', [
        'propiedad_id' => $propiedad->id,
        'tractor' => true,
        'modelo_tractor' => '2020',
        'arado' => true,
    ]);

    $response->assertRedirect('/maquinaria');
    $this->assertDatabaseHas('maquinarias', [
        'propiedad_id' => $propiedad->id,
        'tractor' => true,
    ]);
});

test('user puede ver formulario de edicion de maquinaria', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    $maquinaria = Maquinaria::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->get("/maquinaria/{$maquinaria->id}/edit");

    $response->assertOk();
});

test('user puede actualizar maquinaria', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    $maquinaria = Maquinaria::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->put("/maquinaria/{$maquinaria->id}", [
        'propiedad_id' => $propiedad->id,
        'arado' => true,
        'rastra' => true,
    ]);

    $response->assertRedirect('/maquinaria');
    $this->assertDatabaseHas('maquinarias', [
        'id' => $maquinaria->id,
        'tractor' => false,
        'rastra' => true,
    ]);
});

test('user puede eliminar maquinaria', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    $maquinaria = Maquinaria::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->delete("/maquinaria/{$maquinaria->id}");

    $response->assertRedirect('/maquinaria');
    $this->assertDatabaseMissing('maquinarias', ['id' => $maquinaria->id]);
});

test('maquinaria requiere propiedad_id', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/maquinaria', []);

    $response->assertSessionHasErrors(['propiedad_id']);
});

test('usuario no puede eliminar maquinaria de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();
    $maquinaria = Maquinaria::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->delete("/maquinaria/{$maquinaria->id}");

    $response->assertNotFound();
});
