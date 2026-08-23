<?php

test('preview staff reset password responde 200 en entorno local/testing', function () {
    $response = $this->get('/preview/staff-reset-password');

    $response->assertOk();
});

test('preview user reset password responde 200 en entorno local/testing', function () {
    $response = $this->get('/preview/user-reset-password');

    $response->assertOk();
});

test('preview welcome verification responde 200 en entorno local/testing', function () {
    $response = $this->get('/preview/welcome-verification');

    $response->assertOk();
});
