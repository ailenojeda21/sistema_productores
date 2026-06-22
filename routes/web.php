<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ComercioController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffNewPasswordController;
use App\Http\Controllers\StaffPasswordResetLinkController;
use App\Http\Controllers\StaffProducerController;
use App\Http\Controllers\StaffUserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// ============================================================
// PREVIEW de plantillas de correo (solo desarrollo)
// ============================================================
// Route::get('/preview/staff-reset-password', function () {
//     return view('emails.staff-reset-password', [
//         'url' => 'https://example.com/reset-password/token123',
//         'count' => 60,
//     ]);
// });
//
// Route::get('/preview/user-reset-password', function () {
//     return view('emails.user-reset-password', [
//         'url' => 'https://example.com/reset-password/token456',
//         'count' => 60,
//     ]);
// });
//
// Route::get('/preview/welcome-verification', function () {
//     $user = (object) ['name' => 'Juan Pérez'];
//
//     return view('emails.welcome-verification', [
//         'user' => $user,
//         'verificationUrl' => 'https://example.com/verify-email/token789',
//     ]);
// });

/*
|--------------------------------------------------------------------------
| PÁGINA DE INICIO
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| ================= SISTEMA A =================
| PRODUCTORES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DASHBOARD PRODUCTORES
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    return view('dashboard', [
        'profileCompleteness' => $user->profile_completeness,
        'propiedadesCompleteness' => $user->propiedades_completeness,
        'cultivosCompleteness' => $user->cultivos_completeness,
        'maquinariasCompleteness' => $user->maquinarias_completeness,
        'comercializacionCompleteness' => $user->comercializacion_completeness,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH PRODUCTORES
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.create');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login-producer')
        ->name('login');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'El correo electronico ya ha sido utilizado.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'name.required' => 'El nombre es obligatorio.',
        ]);

        $data['email'] = strtolower($data['email']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    })->middleware('throttle:register');

    // Rutas de recuperacion de contrasena
    require __DIR__.'/auth.php';
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS PRODUCTORES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.updateAvatar');

    Route::get('/profile/avatar', [ProfileController::class, 'editAvatar'])
        ->name('profile.avatar');

    Route::get('/profile/export', [ProfileController::class, 'export'])
        ->name('profile.export');

    // Cultivos
    Route::get('/cultivos/hectareas-disponibles', [CultivoController::class, 'hectareasDisponibles'])
        ->name('cultivos.hectareas-disponibles');
    Route::resource('cultivos', CultivoController::class)->except(['show']);

    // Propiedades
    Route::resource('propiedades', PropiedadController::class);

    // Comercios
    Route::resource('comercios', ComercioController::class);

    // Maquinaria
    Route::resource('maquinaria', MaquinariaController::class)->except(['show']);
});

// RUT files (fuera del grupo auth para permitir ambas guardas: web y staff)
Route::get('/propiedades/{propiedad}/rut', [PropiedadController::class, 'downloadRut'])
    ->name('propiedades.rut');

/*
|--------------------------------------------------------------------------
| ================= SISTEMA B =================
| STAFF (ADMINISTRADORES / AUDITORES)
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH STAFF
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [StaffAuthController::class, 'showLogin'])
        ->name('staff.login');

    Route::post('/login', [StaffAuthController::class, 'login'])
        ->middleware('throttle:login-staff');

    /*
    |--------------------------------------------------------------------------
    | STAFF PASSWORD RESET
    |--------------------------------------------------------------------------
    */
    Route::get('/forgot-password', [StaffPasswordResetLinkController::class, 'create'])
        ->name('staff.password.request');

    Route::post('/forgot-password', [StaffPasswordResetLinkController::class, 'store'])
        ->middleware('throttle:login-staff')
        ->name('staff.password.email');

    Route::get('/reset-password/{token}', [StaffNewPasswordController::class, 'create'])
        ->name('staff.password.reset');

    Route::post('/reset-password', [StaffNewPasswordController::class, 'store'])
        ->name('staff.password.store');

    /*
    |--------------------------------------------------------------------------
    | RUTAS PROTEGIDAS STAFF
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:staff')->group(function () {

        // Dashboard (ADMIN + AUDITOR)
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('staff.dashboard');

        /*
         |--------------------------------------------------------------------------
         | SOLO ADMIN
         |--------------------------------------------------------------------------
         */
        Route::middleware('staff.role:admin')->group(function () {

            // Gestión de Usuarios Staff
            Route::get('/users', [StaffUserController::class, 'index'])
                ->name('staff.users.index');

            Route::get('/users/create', [StaffUserController::class, 'create'])
                ->name('staff.users.create');

            Route::get('/users/{id}/edit', [StaffUserController::class, 'edit'])
                ->name('staff.users.edit');

            Route::patch('/users/{id}', [StaffUserController::class, 'update'])
                ->name('staff.users.update');

            Route::delete('/users/{id}', [StaffUserController::class, 'destroy'])
                ->name('staff.users.destroy');

            Route::post('/users', [StaffUserController::class, 'store'])
                ->name('staff.users.store');

            Route::get('/auditors', function () {
                return inertia('Staff/Auditors/Index');
            })->name('staff.auditors.index');

            Route::post('/auditors', function () {
                // alta auditor
            })->name('staff.auditors.store');

            Route::delete('/auditors/{id}', function ($id) {
                // baja auditor
            })->name('staff.auditors.destroy');

        });

        /*
        |--------------------------------------------------------------------------
        | STAFF PRODUCERS (ADMIN + AUDITOR)
        |--------------------------------------------------------------------------
        */
        Route::get('/producers', [StaffProducerController::class, 'index'])
            ->name('staff.producers.index');

        Route::get('/producers/export', [StaffProducerController::class, 'export'])
            ->name('staff.producers.export');

        Route::get('/producers/{id}', [StaffProducerController::class, 'show'])
            ->name('staff.producers.show');

        // Logout Staff
        Route::post('/logout', [StaffAuthController::class, 'logout'])
            ->name('staff.logout');
    });
});
