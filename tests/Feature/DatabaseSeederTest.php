<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

// Se invoca el seeder directo y no via `$this->seed()`: el comando db:seed pide
// confirmacion en production (ConfirmableTrait) y en el test no hay entrada.
test('en production no se ejecutan los seeders de dominio', function () {
    $this->app->detectEnvironment(fn () => 'production');

    (new DatabaseSeeder)->run();

    // UserSeeder mantiene su propio guard: crea roles pero no usuarios demo.
    expect(Role::pluck('name')->all())->toContain('admin', 'productor');
    expect(DB::table('users')->count())->toBe(0);

    expect(DB::table('propiedades')->count())->toBe(0);
    expect(DB::table('maquinarias')->count())->toBe(0);
    expect(DB::table('cultivos')->count())->toBe(0);
});

test('en local si se ejecutan los seeders de dominio', function () {
    (new DatabaseSeeder)->run();

    expect(DB::table('users')->count())->toBeGreaterThanOrEqual(2);
    expect(DB::table('propiedades')->count())->toBeGreaterThan(0);
    expect(DB::table('maquinarias')->count())->toBeGreaterThan(0);
    expect(DB::table('cultivos')->count())->toBeGreaterThan(0);
});
