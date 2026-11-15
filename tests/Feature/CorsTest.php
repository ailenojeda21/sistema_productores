<?php

use App\Support\CorsOrigins;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/api/_test/ping', fn () => response('ok'));
});

test('la lista de origenes parte de APP_URL y suma los dominios extra', function () {
    expect(CorsOrigins::allowed('https://panel.example.com/', ' https://otro.example.com , https://panel.example.com '))
        ->toBe(['https://panel.example.com', 'https://otro.example.com']);
});

test('la lista de origenes nunca incluye el comodin', function () {
    expect(CorsOrigins::allowed('*', '*'))->toBe([]);
    expect(CorsOrigins::allowed('http://localhost', '*'))->toBe(['http://localhost']);
    expect(CorsOrigins::allowed(null, 'https://panel.example.com,*'))
        ->toBe(['https://panel.example.com']);

    expect(config('cors.allowed_origins'))->not->toContain('*');
});

test('sin APP_URL ni dominios extra la lista queda vacia', function () {
    expect(CorsOrigins::allowed(null, null))->toBe([]);
    expect(CorsOrigins::allowed('', '  '))->toBe([]);
});

test('un origen permitido recibe los encabezados CORS', function () {
    config(['cors.allowed_origins' => ['https://panel.example.com']]);

    $this->get('/api/_test/ping', ['Origin' => 'https://panel.example.com'])
        ->assertOk()
        ->assertHeader('Access-Control-Allow-Origin', 'https://panel.example.com');
});

test('un preflight de un origen permitido responde con los encabezados CORS', function () {
    config(['cors.allowed_origins' => ['https://panel.example.com']]);

    $this->call('OPTIONS', '/api/staff/login', [], [], [], [
        'HTTP_ORIGIN' => 'https://panel.example.com',
        'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
    ])
        ->assertNoContent()
        ->assertHeader('Access-Control-Allow-Origin', 'https://panel.example.com');
});

test('un origen no permitido no recibe encabezados CORS', function () {
    // Con una sola origen permitida, Fruitcake responde esa origen sin mirar
    // quien llama (isSingleOriginAllowed), por eso se configuran dos.
    config(['cors.allowed_origins' => ['https://panel.example.com', 'https://admin.example.com']]);

    $response = $this->get('/api/_test/ping', ['Origin' => 'https://otro.example.com']);

    $response->assertOk();
    expect($response->headers->has('Access-Control-Allow-Origin'))->toBeFalse();
});
