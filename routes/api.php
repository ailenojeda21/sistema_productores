<?php

use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\PropiedadController;
use Illuminate\Support\Facades\Route;

// Rutas protegidas por autenticación
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('propiedades', PropiedadController::class);
    Route::apiResource('archivos', ArchivoController::class);
    Route::apiResource('maquinarias', MaquinariaController::class);
    Route::apiResource('cultivos', CultivoController::class);

});

// Puedes agregar rutas públicas aquí si es necesario
