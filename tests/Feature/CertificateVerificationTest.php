<?php

use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Route;

test('la verificacion muestra datos minimos sin datos sensibles para invitados', function () {
    $user = User::factory()->create([
        'dni' => '40111222',
        'telefono' => '2615551234',
        'direccion' => 'Calle Secreta 999',
    ]);

    $token = CertificateService::token(CertificateService::TIPO_COMPROBANTE, $user->id);

    $this->get("/verificar/{$token}")
        ->assertOk()
        ->assertSee($user->name)
        ->assertSee('Comprobante de Registro')
        ->assertSee('Verificado el')
        ->assertDontSee('40111222')
        ->assertDontSee('2615551234')
        ->assertDontSee('Calle Secreta 999');
});

test('un token forjado devuelve mensaje neutro sin exponer informacion', function () {
    $user = User::factory()->create(['name' => 'Nombre Oculto']);

    $this->get('/verificar/comprobante-'.$user->id.'-deadbeefdeadbeefdeadbeefdeadbeef')
        ->assertOk()
        ->assertSee('No se pudo verificar este documento')
        ->assertDontSee('Nombre Oculto')
        ->assertDontSee('Comprobante de Registro');
});

test('un token con formato invalido devuelve mensaje neutro', function () {
    $this->get('/verificar/garbage-token')
        ->assertOk()
        ->assertSee('No se pudo verificar este documento');
});

test('el token es estable para el mismo productor y tipo de documento', function () {
    $primero = CertificateService::token(CertificateService::TIPO_INFORME, 7);
    sleep(1);
    $segundo = CertificateService::token(CertificateService::TIPO_INFORME, 7);

    expect($segundo)->toBe($primero);
});

test('la ruta de verificar no requiere autenticacion y aplica throttle', function () {
    $route = Route::getRoutes()->getByName('verificar');

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())->toContain('throttle:10,1');

    $user = User::factory()->create();
    $token = CertificateService::token(CertificateService::TIPO_COMPROBANTE, $user->id);

    $this->get("/verificar/{$token}")->assertOk();
});

test('el servicio genera un qr png embebible', function () {
    $url = CertificateService::verificationUrl(CertificateService::TIPO_COMPROBANTE, 1);

    expect(CertificateService::qrDataUri($url))->toStartWith('data:image/png;base64,')
        ->and($url)->toContain('/verificar/');
});
