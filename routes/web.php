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


Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



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

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    //mostrar el index de propieades
    Route::get('/propiedades', function(){
        return view('propiedades.index', ['propiedades'=>Propiedad::all()] );
    })->name('propiedades.index');

    //mostrar el formulario de la nueva propiead
    Route::get('/propiedades/create', function(){
        return view('propiedades.create' );
    })->name('propiedades.create');

    Route::post('/propiedades/store', function(Request $request){
        
        $validated = $request->validate([

            'direccion'=>'required|string|max:100',
            'ubicacion'=>'string',
            'es_propietario'=>'boolean',
            'hectareas'=>'float',
            'derecho_riego'=>'boolean'
        ]);

            dd($request);

        $np = Propiedad::create([

            'usuario_id'=>1,
            'direccion'=>$validated['direccion'],
            'ubicacion'=>$validated['ubicacion'],
            'es_propietario'=>$validated['es_propietario'],
            'hectareas'=>$validated['hectareas'],
            'derecho_riego'=>$validated['derecho_riego'],
        ]);

        dd($np);

        return redirect()->route('propiedades.index')->with('success','Propiedad creada');

    })->name('propiedades.store');


    Route::get('/archivos', function () {
        return view('archivos.index');
    })->name('archivos.index');

    Route::get('/maquinaria', function () {
        return view('maquinaria.index');
    })->name('maquinaria.index');


  //mostrar el index de cultivos
    Route::get('/cultivos', function(){
        return view('cultivos.index', ['cultivos'=>Cultivo::all()] );
    })->name('cultivos.index');

    Route::get('/cultivos/create', function(){

        $lista_tecnologias = ["Aspersion","Tradicional"];


        return view('cultivos.create', ['lista_tecnologias' => $lista_tecnologias] );
    })->name('cultivos.create');

    Route::post('/cultivos/store', function(Request $request){



    })->name('cultivos.store');

});

require __DIR__.'/auth.php';
