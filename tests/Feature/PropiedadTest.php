<?php

use App\Models\User;
use App\Models\Propiedad;
use App\Models\Cultivo;
use App\Models\Maquinaria;
use App\Models\Comercio;

test('user puede ver listado de propiedades', function () {
    $user = User::factory()->create();
    Propiedad::factory()->count(3)->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->get('/propiedades');

    $response->assertOk();
});

test('user puede crear propiedad', function () {
    $user = User::factory()->create();
    $distritos = array_keys(Propiedad::DISTRITOS);

    $response = $this->actingAs($user)->post('/propiedades', [
        'calle' => 'Calle Falsa',
        'numeracion' => '123',
        'distrito' => $distritos[0],
        'hectareas' => '10.5',
        'derecho_riego' => false,
        'tipo_derecho_riego' => 'Subterráneo',
        'rut' => false,
        'tipo_tenencia' => 'propietario',
        'lat' => '-33.0',
        'lng' => '-68.5',
    ]);

    $response->assertRedirect('/propiedades');
    $this->assertDatabaseHas('propiedades', [
        'usuario_id' => $user->id,
        'calle' => 'Calle Falsa',
    ]);
});

test('user puede ver formulario de edicion de propiedad', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->get("/propiedades/{$propiedad->id}/edit");

    $response->assertOk();
});

test('user puede actualizar propiedad', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->put("/propiedades/{$propiedad->id}", [
        'calle' => 'Nueva Calle',
        'numeracion' => '456',
        'distrito' => $propiedad->distrito,
        'hectareas' => '20.0',
        'derecho_riego' => $propiedad->derecho_riego,
        'tipo_derecho_riego' => $propiedad->tipo_derecho_riego,
        'rut' => $propiedad->rut,
        'tipo_tenencia' => 'propietario',
        'lat' => $propiedad->lat,
        'lng' => $propiedad->lng,
    ]);

    $response->assertRedirect('/propiedades');
    $this->assertDatabaseHas('propiedades', [
        'id' => $propiedad->id,
        'calle' => 'Nueva Calle',
    ]);
});

test('user puede eliminar propiedad', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();

    $response = $this->actingAs($user)->delete("/propiedades/{$propiedad->id}");

    $response->assertRedirect('/propiedades');
    $this->assertDatabaseMissing('propiedades', ['id' => $propiedad->id]);
});

test('propiedad requiere campos obligatorios', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/propiedades', []);

    $response->assertSessionHasErrors(['calle', 'numeracion', 'distrito', 'hectareas', 'lat', 'lng', 'tipo_tenencia']);
});

test('usuario no puede editar propiedad de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->get("/propiedades/{$propiedad->id}/edit");

    $response->assertNotFound();
});

test('usuario no puede actualizar propiedad de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->put("/propiedades/{$propiedad->id}", [
            'calle' => 'Hackeada',
            'hectareas' => '99',
            'tipo_tenencia' => 'propietario',
        ]);

    $response->assertNotFound();
});

test('usuario no puede eliminar propiedad de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create();

    $response = $this->actingAs($attacker)
        ->delete("/propiedades/{$propiedad->id}");

    $response->assertNotFound();
});
