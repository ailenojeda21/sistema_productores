@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-green-100 to-blue-200">
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-lg w-full flex flex-col items-center">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616554.png" alt="Logo" class="w-24 h-24 mb-4">
        <h1 class="text-3xl font-bold text-green-700 mb-2">Bienvenido al Sistema Agrícola</h1>
        <p class="text-gray-700 mb-6 text-center">Gestiona productores, propiedades, maquinaria, cultivos y más.<br>Accede con tu usuario o regístrate para comenzar.</p>
        <div class="flex space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold shadow">Ingresar</a>
            <a href="{{ route('register') }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold shadow">Registrarse</a>
        </div>
    </div>
</div>
@endsection
