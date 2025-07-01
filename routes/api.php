<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\CultivoController;

// Rutas protegidas por autenticación
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('propiedades', PropiedadController::class);
    Route::apiResource('archivos', ArchivoController::class);
    Route::apiResource('maquinarias', MaquinariaController::class);
    Route::apiResource('cultivos', CultivoController::class);

});

// Puedes agregar rutas públicas aquí si es necesario
