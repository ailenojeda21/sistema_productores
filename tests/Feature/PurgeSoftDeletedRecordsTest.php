<?php

use App\Models\Propiedad;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function purgeTestTrashUser(?string $rutFile = null): array
{
    Storage::fake('rut_files');

    $user = User::factory()->create([
        'name' => 'Productor Antiguo',
        'email' => 'antiguo-'.uniqid().'@test.com',
    ]);

    $propiedad = Propiedad::factory()->for($user, 'usuario')->create([
        'rut_archivo' => $rutFile,
    ]);

    if ($rutFile !== null) {
        Storage::disk('rut_files')->put($rutFile, 'contenido-pdf-falso');
    }

    $user->delete();
    DB::table('users')->where('id', $user->id)->update([
        'deleted_at' => now()->subDays(400),
    ]);

    return [$user, $propiedad];
}

test('purge elimina permanentemente usuarios vencidos y sus archivos rut', function () {
    [$user, $propiedad] = purgeTestTrashUser('ruts/viejo.pdf');

    $this->artisan('app:purge-soft-deleted-records', ['--days' => 365])->assertSuccessful();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('propiedades', ['id' => $propiedad->id]);
    Storage::disk('rut_files')->assertMissing('ruts/viejo.pdf');
});

test('purge respeta el periodo de retencion', function () {
    Storage::fake('rut_files');

    $user = User::factory()->create(['email' => 'reciente@test.com']);
    $user->delete();

    $this->artisan('app:purge-soft-deleted-records', ['--days' => 365])->assertSuccessful();

    $this->assertSoftDeleted($user);
});

test('purge dry run no elimina nada y lista los afectados', function () {
    [$user, $propiedad] = purgeTestTrashUser('ruts/dryrun.pdf');

    $this->artisan('app:purge-soft-deleted-records', [
        '--days' => 365,
        '--dry-run' => true,
    ])->assertSuccessful();

    $this->assertSoftDeleted($user);
    $this->assertDatabaseHas('propiedades', ['id' => $propiedad->id]);
    Storage::disk('rut_files')->assertExists('ruts/dryrun.pdf');
});

test('purge no toca usuarios activos ni staff vigente', function () {
    [$trashedUser] = purgeTestTrashUser();

    $activeUser = User::factory()->create(['email' => 'activo@test.com']);
    $staff = StaffUser::factory()->create();

    $this->artisan('app:purge-soft-deleted-records', ['--days' => 365])->assertSuccessful();

    $this->assertDatabaseHas('users', ['id' => $activeUser->id]);
    $this->assertDatabaseHas('staff_users', ['id' => $staff->id]);
    $this->assertDatabaseMissing('users', ['id' => $trashedUser->id]);
});
