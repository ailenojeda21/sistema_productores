<?php

use App\Models\User;
use App\Models\Propiedad;
use App\Models\Cultivo;

test('user puede ver listado de cultivos', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->count(2)->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->get('/cultivos');

    $response->assertOk();
});

test('user puede crear cultivo', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create([
        'hectareas' => 100,
    ]);

    $response = $this->actingAs($user)->post('/cultivos', [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Verano',
        'hectareas' => '5.0',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    $response->assertRedirect('/cultivos');
    $this->assertDatabaseHas('cultivos', [
        'propiedad_id' => $propiedad->id,
        'variedad' => 'Tomate Redondo',
    ]);
});

test('user puede ver formulario de edicion de cultivo', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->get("/cultivos/{$cultivo->id}/edit");

    $response->assertOk();
});

test('user puede actualizar cultivo', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create([
        'hectareas' => 100,
    ]);
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->put("/cultivos/{$cultivo->id}", [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Vitícola',
        'variedad' => 'Malbec',
        'estacion' => 'Otoño',
        'hectareas' => '3.0',
        'manejo_cultivo' => 'Organico',
        'tecnologia_riego' => 'Surco',
    ]);

    $response->assertRedirect('/cultivos');
    $this->assertDatabaseHas('cultivos', [
        'id' => $cultivo->id,
        'variedad' => 'Malbec',
    ]);
});

test('user puede eliminar cultivo', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->delete("/cultivos/{$cultivo->id}");

    $response->assertRedirect('/cultivos');
    $this->assertDatabaseMissing('cultivos', ['id' => $cultivo->id]);
});

test('cultivo requiere campos obligatorios', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/cultivos', []);

    $response->assertSessionHasErrors(['propiedad_id']);
});

test('usuario no puede editar cultivo de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->get("/cultivos/{$cultivo->id}/edit");

    $response->assertForbidden();
});

test('usuario no puede actualizar cultivo de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create(['hectareas' => 100]);
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->put("/cultivos/{$cultivo->id}", [
            'propiedad_id' => $propiedad->id,
            'tipo' => 'Hortícola',
            'variedad' => 'Tomate Redondo',
            'estacion' => 'Verano',
            'hectareas' => '3.0',
            'manejo_cultivo' => 'Convencional',
            'tecnologia_riego' => 'Goteo',
        ]);

    $response->assertForbidden();
});

test('usuario no puede eliminar cultivo de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();
    $cultivo = Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->delete("/cultivos/{$cultivo->id}");

    $response->assertForbidden();
});

test('invitado no puede ver listado de cultivos', function () {
    $response = $this->get('/cultivos');

    $response->assertRedirect('/login');
});

test('invitado no puede crear cultivo', function () {
    $response = $this->post('/cultivos', [
        'propiedad_id' => 1,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Verano',
        'hectareas' => '5.0',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    $response->assertRedirect('/login');
});
