@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-200">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Ingreso al Sistema Agrícola</h2>
        @if(session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="password">Contraseña</label>
                <input id="password" type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded transition">Ingresar</button>
        </form>
        <div class="mt-6 text-center">
            <span class="text-gray-600">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline ml-1">Regístrate</a>
        </div>
    </div>
</div>
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Ingreso al Sistema Agrícola</h2>
        @if(session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="password">Contraseña</label>
                <input id="password" type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="#" class="text-sm text-green-600 hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded transition">Ingresar</button>
        </form>
        <div class="mt-6 text-center">
            <span class="text-gray-600">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline ml-1">Regístrate</a>
        </div>
    </div>
{{-- </div> --}}
@endsection
