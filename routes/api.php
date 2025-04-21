<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\ImplementoController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\TecnologiaRiegoController;

// Rutas protegidas por autenticación
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('propiedades', PropiedadController::class);
    Route::apiResource('archivos', ArchivoController::class);
    Route::apiResource('maquinarias', MaquinariaController::class);
    Route::apiResource('implementos', ImplementoController::class);
    Route::apiResource('cultivos', CultivoController::class);
    Route::apiResource('tecnologia-riegos', TecnologiaRiegoController::class);
});

// Puedes agregar rutas públicas aquí si es necesario
