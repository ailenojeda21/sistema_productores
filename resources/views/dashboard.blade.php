@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-200">
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-xl w-full">
        <h1 class="text-3xl font-bold text-green-700 mb-4">Panel de Control</h1>
        <p class="text-gray-700 mb-6">Bienvenido al sistema agrícola. Desde aquí puedes acceder a la gestión de productores, propiedades, maquinaria, implementos, cultivos y tecnologías de riego.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('propiedades.index') }}" class="block px-6 py-4 bg-green-600 text-white rounded-lg text-center font-semibold shadow hover:bg-green-700">Propiedades</a>

<a href="{{ route('archivos.index') }}" class="block px-6 py-4 bg-blue-600 text-white rounded-lg text-center font-semibold shadow hover:bg-blue-700">Archivos</a>

<a href="{{ route('maquinaria.index') }}" class="block px-6 py-4 bg-yellow-500 text-white rounded-lg text-center font-semibold shadow hover:bg-yellow-600">Maquinaria</a>

<a href="{{ route('implementos.index') }}" class="block px-6 py-4 bg-indigo-600 text-white rounded-lg text-center font-semibold shadow hover:bg-indigo-700">Implementos</a>

<a href="{{ route('cultivos.index') }}" class="block px-6 py-4 bg-purple-600 text-white rounded-lg text-center font-semibold shadow hover:bg-purple-700">Cultivos</a>

<a href="{{ route('tecnologia_riego.index') }}" class="block px-6 py-4 bg-teal-600 text-white rounded-lg text-center font-semibold shadow hover:bg-teal-700">Tecnología Riego</a>

        </div>
    </div>
</div>
@endsection
