<?php

use App\Models\StaffUser;
use App\Models\User;
use App\Models\Propiedad;
use App\Models\Cultivo;

test('staff admin puede ver listado de productores', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.index'));

    $response->assertOk();
});

test('staff auditor puede ver listado de productores', function () {
    $staff = StaffUser::factory()->create(['role' => 'auditor']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.index'));

    $response->assertOk();
});

test('staff puede buscar productores por nombre', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Juan Perez']);
    User::factory()->create(['name' => 'Maria Gomez']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.index', ['name' => 'Juan']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Staff/Producers/Index')
        ->has('producers.data', 1)
    );
});

test('staff puede buscar productores por dni', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    User::factory()->create(['dni' => '12345678', 'name' => 'User A']);
    User::factory()->create(['dni' => '87654321', 'name' => 'User B']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.index', ['dni' => '1234']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Staff/Producers/Index')
        ->has('producers.data', 1)
    );
});

test('staff puede buscar productores por variedad', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Productor Vinedo']);
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->for($propiedad, 'propiedad')->create([
        'tipo' => 'Vitícola',
        'variedad' => 'Malbec',
    ]);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.index', ['variedad' => 'Malbec']));

    $response->assertOk();
});

test('staff puede ver detalle de un productor', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->for($propiedad, 'propiedad')->create();

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.show', $user->id));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Staff/Producers/Show')
        ->has('producer')
        ->has('propiedades')
        ->has('cultivos')
        ->has('maquinarias')
        ->has('stats')
    );
});

test('staff puede exportar productores', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    User::factory()->count(3)->create();

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel');
});

test('staff puede exportar productores filtrados por distrito', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'User Distrito']);
    Propiedad::factory()->for($user, 'usuario')->create([
        'distrito' => 'la-pega',
    ]);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export', ['distrito' => 'la-pega']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.ms-excel');
});
