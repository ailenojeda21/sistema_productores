<?php

use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use App\Models\Propiedad;
use App\Models\Cultivo;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Página de inicio
Route::get('/', function () {
    return view('home');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Login
Route::get('/login', function (Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no son válidas.',
    ])->withInput();
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Registro
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();
    return redirect('/dashboard');
});

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->middleware('auth')->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('profile.update');

    // Archivos
    Route::get('/archivos', function () {
        return view('archivos.index');
    })->name('archivos.index');

    // Maquinaria (todas las rutas CRUD)
    Route::resource('maquinaria', \App\Http\Controllers\MaquinariaController::class);

    // Propiedades (todas las rutas CRUD con nombres correctos)
    Route::resource('propiedades', PropiedadController::class);

    // Cultivos
    Route::get('/cultivos', function () {
        return view('cultivos.index', ['cultivos' => Cultivo::all()]);
    })->name('cultivos.index');

    Route::get('/cultivos/create', function () {
        $lista_tecnologias = ["Aspersion", "Tradicional"];
        return view('cultivos.create', ['lista_tecnologias' => $lista_tecnologias]);
    })->name('cultivos.create');

    Route::post('/cultivos/store', function (Request $request) {
        // lógica para guardar cultivo
    })->name('cultivos.store');

    // Comercios (todas las rutas CRUD)
    Route::resource('comercios', \App\Http\Controllers\ComercioController::class);
});

require __DIR__ . '/auth.php';
