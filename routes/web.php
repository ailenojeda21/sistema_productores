<?php

use App\Http\Controllers\ComercioController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', function () {
    return view('home');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Login
Route::middleware('web')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.create');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Registro
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

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NUEVA RUTA → Actualizar avatar
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

    // Asegurarse de tener el resource para maquinaria (no duplicar si ya existe)
    Route::resource('maquinaria', MaquinariaController::class);
});
