<?php

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
Route::post('/staff/login', [App\Http\Controllers\StaffApiAuthController::class, 'login'])
    ->middleware('throttle:login-staff-api');

// Rutas protegidas con token Sanctum
Route::middleware(['auth:staff-api'])->prefix('staff')->group(function () {

    // Sesión
    Route::post('/logout', [App\Http\Controllers\StaffApiAuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\StaffApiAuthController::class, 'me']);

    // Dashboard (admin + auditor)
    Route::get('/dashboard', [App\Http\Controllers\StaffDashboardController::class, 'index']);

    // Productores (admin + auditor)
    Route::get('/producers', [App\Http\Controllers\StaffProducerController::class, 'index']);
    Route::get('/producers/export', [App\Http\Controllers\StaffProducerController::class, 'export']);
    Route::get('/producers/{id}', [App\Http\Controllers\StaffProducerController::class, 'show']);

    // Solo admin
    Route::middleware('staff.role:admin')->group(function () {
        Route::get('/users', [App\Http\Controllers\StaffUserController::class, 'index']);
        Route::get('/users/create', [App\Http\Controllers\StaffUserController::class, 'create']);
        Route::post('/users', [App\Http\Controllers\StaffUserController::class, 'store']);
        Route::get('/users/{id}/edit', [App\Http\Controllers\StaffUserController::class, 'edit']);
        Route::patch('/users/{id}', [App\Http\Controllers\StaffUserController::class, 'update']);
        Route::delete('/users/{id}', [App\Http\Controllers\StaffUserController::class, 'destroy']);
    });
});
