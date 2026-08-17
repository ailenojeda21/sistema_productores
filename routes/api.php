<?php

use App\Http\Controllers\StaffApiAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffProducerController;
use App\Http\Controllers\StaffUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API — Sistema de Productores
|--------------------------------------------------------------------------
|
| =========== TIPOS DE USUARIO ===========
|
| 1) PRODUCTORES (guard 'web', modelo User, auth:sanctum)
|    - Usan Spatie HasRoles para permisos granulares.
|    - Middleware 'role:admin' verifica roles Spatie.
|    - Ruta: GET /api/producers/...
|
| 2) STAFF (guard 'staff-api', modelo StaffUser, Sanctum tokens)
|    - Usan columna 'role' (admin/auditor), NO Spatie.
|    - Middleware 'staff.role:admin' verifica StaffUser->role.
|    - Ruta: GET /api/staff/...
|
| Para generar tokens staff (POST /api/staff/login):
|   { "email": "...", "password": "..." }
|   Responde: { "token": "...", "user": { ... } }
|
*/

// =====================================================================
// API PARA STAFF (administradores / auditores)
// =====================================================================

// Login (público, sin auth)
Route::post('/staff/login', [StaffApiAuthController::class, 'login'])
    ->middleware('throttle:login-staff-api');

// Rutas protegidas con token Sanctum
Route::middleware(['auth:staff-api', 'staff.active', 'throttle:60,1'])->prefix('staff')->group(function () {

    // Sesión
    Route::post('/logout', [StaffApiAuthController::class, 'logout']);
    Route::get('/me', [StaffApiAuthController::class, 'me']);

    // Dashboard (admin + auditor)
    Route::get('/dashboard', [StaffDashboardController::class, 'index']);

    // Productores (admin + auditor)
    Route::get('/producers', [StaffProducerController::class, 'index']);
    Route::get('/producers/{id}', [StaffProducerController::class, 'show']);

    // Solo admin
    Route::middleware('staff.role:admin')->group(function () {
        Route::get('/producers/export', [StaffProducerController::class, 'export']);
        Route::get('/users', [StaffUserController::class, 'index']);
        Route::get('/users/create', [StaffUserController::class, 'create']);
        Route::post('/users', [StaffUserController::class, 'store']);
        Route::get('/users/{id}/edit', [StaffUserController::class, 'edit']);
        Route::patch('/users/{id}', [StaffUserController::class, 'update']);
        Route::delete('/users/{id}', [StaffUserController::class, 'destroy']);
    });
});
