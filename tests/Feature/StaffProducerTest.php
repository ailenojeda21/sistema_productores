<?php

use App\Models\Cultivo;
use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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

test('export filtrado registra log con user_id y filtros sin PII', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Juan Perez']);

    Log::spy();

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export', ['name' => 'Juan']));

    $response->assertOk();
    Log::shouldHaveReceived('info')
        ->with('staff.producers.export', Mockery::on(function ($ctx) {
            return isset($ctx['user_id']) && $ctx['tipo'] === 'filtrado'
                && in_array('name', $ctx['filtros'])
                && ! str_contains(json_encode($ctx), 'Juan');
        }))
        ->once();
});

test('export completo registra log con user_id y tipo completo', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);

    Log::spy();

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export-all'));

    $response->assertOk();
    Log::shouldHaveReceived('info')
        ->with('staff.producers.export', Mockery::on(function ($ctx) {
            return isset($ctx['user_id']) && $ctx['tipo'] === 'completo'
                && $ctx['filtros'] === [];
        }))
        ->once();
});

test('admin puede exportar productores', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Juan Perez', 'dni' => '11111111']);
    User::factory()->create(['name' => 'Maria Gomez', 'dni' => '22222222']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->assertGreaterThan(2000, strlen($response->getContent()));
});

test('auditor puede exportar productores', function () {
    $staff = StaffUser::factory()->create(['role' => 'auditor']);
    User::factory()->create(['name' => 'Juan Perez', 'dni' => '11111111']);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->assertGreaterThan(1000, strlen($response->getContent()));
});

test('staff puede exportar productores filtrados por distrito', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'User Distrito']);
    Propiedad::factory()->for($user, 'usuario')->create([
        'distrito' => 'La Pega',
    ]);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export', ['distrito' => 'la pega']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->assertGreaterThan(1000, strlen($response->getContent()));
});

test('staff puede exportar productores filtrados por variedad', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Productor Vinedo']);
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->for($propiedad, 'propiedad')->create([
        'variedad' => 'Malbec',
        'hectareas' => 5.5,
    ]);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export', ['variedad' => 'Malbec']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->assertGreaterThan(1000, strlen($response->getContent()));
});

test('staff puede exportar productores filtrados por tipo', function () {
    $staff = StaffUser::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Productor Tipo']);
    $propiedad = Propiedad::factory()->for($user, 'usuario')->create();
    Cultivo::factory()->for($propiedad, 'propiedad')->create([
        'tipo' => 'Vitícola',
        'hectareas' => 3.2,
    ]);

    $response = $this->actingAs($staff, 'staff')
        ->get(route('staff.producers.export', ['tipo' => 'Vitícola']));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->assertGreaterThan(1000, strlen($response->getContent()));
});
