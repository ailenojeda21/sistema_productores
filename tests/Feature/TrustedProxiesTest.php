<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/_test/ip', fn (Request $request) => response()->json([
        'ip' => $request->ip(),
    ]));
});

test('acepta X-Forwarded-For cuando el proxy esta en la lista de confianza', function () {
    config(['trustedproxy.proxies' => '10.1.2.3']);

    $this->withServerVariables(['REMOTE_ADDR' => '10.1.2.3'])
        ->getJson('/_test/ip', ['X-Forwarded-For' => '203.0.113.7'])
        ->assertOk()
        ->assertJson(['ip' => '203.0.113.7']);
});

test('acepta una lista de proxies separada por comas', function () {
    config(['trustedproxy.proxies' => '192.0.2.10, 10.1.2.3']);

    $this->withServerVariables(['REMOTE_ADDR' => '10.1.2.3'])
        ->getJson('/_test/ip', ['X-Forwarded-For' => '203.0.113.7'])
        ->assertOk()
        ->assertJson(['ip' => '203.0.113.7']);
});

test('el comodin confia en la IP que conecta, cualquiera sea', function () {
    config(['trustedproxy.proxies' => '*']);

    $this->withServerVariables(['REMOTE_ADDR' => '10.1.2.3'])
        ->getJson('/_test/ip', ['X-Forwarded-For' => '203.0.113.7'])
        ->assertOk()
        ->assertJson(['ip' => '203.0.113.7']);

    // `*` asume que toda conexion viene de un proxy, por eso solo es seguro
    // cuando la aplicacion no es accesible directamente.
    $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
        ->getJson('/_test/ip', ['X-Forwarded-For' => '203.0.113.9'])
        ->assertOk()
        ->assertJson(['ip' => '203.0.113.9']);
});

test('ignora X-Forwarded-For cuando TRUSTED_PROXIES no esta definido', function () {
    config(['trustedproxy.proxies' => null]);

    $this->withServerVariables(['REMOTE_ADDR' => '10.1.2.3'])
        ->getJson('/_test/ip', ['X-Forwarded-For' => '203.0.113.7'])
        ->assertOk()
        ->assertJson(['ip' => '10.1.2.3']);
});
