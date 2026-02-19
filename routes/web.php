<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ComercioController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffUserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffProducerController;

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
    return view('dashboard');
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
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $data['email'] = strtolower($data['email']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    });
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

    // Cultivos
    Route::get('/cultivos/hectareas-disponibles', [CultivoController::class, 'hectareasDisponibles'])
        ->name('cultivos.hectareas-disponibles');
    Route::resource('cultivos', CultivoController::class);

    // Propiedades
    Route::resource('propiedades', PropiedadController::class);

    // Comercios
    Route::resource('comercios', ComercioController::class);

    // Maquinaria
    Route::resource('maquinaria', MaquinariaController::class);
});

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

    Route::post('/login', [StaffAuthController::class, 'login']);

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

        Route::get('/producers/{id}', [StaffProducerController::class, 'show'])
            ->name('staff.producers.show');

        // Logout Staff
        Route::post('/logout', [StaffAuthController::class, 'logout'])
            ->name('staff.logout');
    });
});
