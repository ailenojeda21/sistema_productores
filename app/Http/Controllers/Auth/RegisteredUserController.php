<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [
            'email.unique' => 'El correo electronico ya ha sido utilizado.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'name.required' => 'El nombre es obligatorio.',
        ]);

        $data['email'] = strtolower($data['email']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'dni' => '',
            'telefono' => '',
            'direccion' => '',
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice');
    }
}
