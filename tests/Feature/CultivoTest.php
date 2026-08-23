<?php

use App\Models\Cultivo;
use App\Models\Propiedad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

test('user puede ver listado de cultivos', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->count(2)->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($user)->get('/cultivos');

    $response->assertOk();
});

test('la creacion simultanea de cultivos nunca excede las hectareas de la propiedad', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create(['hectareas' => 10]);

    $baseLevel = DB::transactionLevel();
    $fired = false;
    Propiedad::retrieved(function ($p) use (&$fired, $propiedad, $baseLevel) {
        if ($fired || $p->id !== $propiedad->id || DB::transactionLevel() <= $baseLevel) {
            return;
        }
        $fired = true;

        Cultivo::withoutEvents(fn () => Cultivo::factory()->for($propiedad, 'propiedad')->create([
            'hectareas' => 6,
        ]));
    });

    $response = $this->actingAs($user)->post('/cultivos', [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Verano',
        'hectareas' => '5',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    expect($response->status())->toBe(302)
        ->and($response->exception)->toBeInstanceOf(ValidationException::class);

    $response->assertSessionHasErrors(['hectareas']);

    $total = (float) Cultivo::where('propiedad_id', $propiedad->id)->sum('hectareas');
    expect($total)->toBeLessThanOrEqual(10.0)
        ->and(Cultivo::where('propiedad_id', $propiedad->id)->count())->toBe(0);

    Propiedad::flushEventListeners();
    Propiedad::clearBootedModels();
});

test('se puede cultivar exactamente la superficie disponible', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create(['hectareas' => 10]);

    $response = $this->actingAs($user)->post('/cultivos', [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Vitícola',
        'variedad' => 'Malbec',
        'estacion' => 'Primavera',
        'hectareas' => '10',
        'manejo_cultivo' => 'Organico',
        'tecnologia_riego' => 'Surco',
    ]);

    $response->assertRedirect('/cultivos')
        ->assertSessionHasNoErrors();
    $this->assertDatabaseHas('cultivos', [
        'propiedad_id' => $propiedad->id,
        'hectareas' => 10,
    ]);
});

test('no se puede ampliar un cultivo mas alla de la superficie disponible', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create(['hectareas' => 10]);
    $cultivoA = Cultivo::factory()->for($propiedad, 'propiedad')->create(['hectareas' => 7]);
    Cultivo::factory()->for($propiedad, 'propiedad')->create(['hectareas' => 4]);

    $response = $this->actingAs($user)->put("/cultivos/{$cultivoA->id}", [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Verano',
        'hectareas' => '9.5',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    $response->assertSessionHasErrors(['hectareas']);
    $this->assertDatabaseHas('cultivos', [
        'id' => $cultivoA->id,
        'hectareas' => 7,
    ]);
});

test('estacion fuera del catalogo es rechazada', function () {
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create(['hectareas' => 100]);

    $response = $this->actingAs($user)->post('/cultivos', [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Invernal',
        'hectareas' => '5',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    $response->assertSessionHasErrors(['estacion']);
    $this->assertDatabaseCount('cultivos', 0);
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
    $propiedadOwner = Propiedad::factory()->for($owner, 'usuario')->create(['hectareas' => 100]);
    $propiedadAttacker = Propiedad::factory()->for($attacker, 'usuario')->create(['hectareas' => 100]);
    $cultivo = Cultivo::factory()->for($propiedadOwner, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->put("/cultivos/{$cultivo->id}", [
            'propiedad_id' => $propiedadAttacker->id,
            'tipo' => 'Hortícola',
            'variedad' => 'Tomate Redondo',
            'estacion' => 'Verano',
            'hectareas' => '3.0',
            'manejo_cultivo' => 'Convencional',
            'tecnologia_riego' => 'Goteo',
        ]);

    $response->assertForbidden();
});

test('usuario no puede crear cultivo en propiedad de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $propiedad = Propiedad::factory()->for($owner, 'usuario')->create(['hectareas' => 100]);

    $response = $this->actingAs($attacker)->post('/cultivos', [
        'propiedad_id' => $propiedad->id,
        'tipo' => 'Hortícola',
        'variedad' => 'Tomate Redondo',
        'estacion' => 'Verano',
        'hectareas' => '5.0',
        'manejo_cultivo' => 'Convencional',
        'tecnologia_riego' => 'Goteo',
    ]);

    $response->assertSessionHasErrors(['propiedad_id']);
    $this->assertDatabaseCount('cultivos', 0);
});

test('usuario no puede mover cultivo propio a propiedad de otro usuario', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $miPropiedad = Propiedad::factory()->for($attacker, 'usuario')->create(['hectareas' => 100]);
    $propiedadAjena = Propiedad::factory()->for($owner, 'usuario')->create(['hectareas' => 100]);
    $cultivo = Cultivo::factory()->for($miPropiedad, 'propiedad')->create();

    $response = $this->actingAs($attacker)
        ->put("/cultivos/{$cultivo->id}", [
            'propiedad_id' => $propiedadAjena->id,
            'tipo' => 'Hortícola',
            'variedad' => 'Tomate Redondo',
            'estacion' => 'Verano',
            'hectareas' => '5.0',
            'manejo_cultivo' => 'Convencional',
            'tecnologia_riego' => 'Goteo',
        ]);

    $response->assertSessionHasErrors(['propiedad_id']);
    $this->assertDatabaseHas('cultivos', [
        'id' => $cultivo->id,
        'propiedad_id' => $miPropiedad->id,
    ]);
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
