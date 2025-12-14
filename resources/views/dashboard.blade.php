@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:flex bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl flex-col items-center">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-48 mb-6">
    <h2 class="text-2xl font-bold text-azul-marino mb-4">Bienvenido {{ Auth::user()->name }} al Sistema Agrícola Lavalle</h2>
    <p class="text-gray-700">Aquí podrás gestionar tus propiedades, cultivos, maquinarias y más.</p>
</div>

<!-- Mobile View -->
<div class="lg:hidden bg-white rounded-lg shadow-lg p-6 w-full flex flex-col items-center">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-32 mb-4">
    <h2 class="text-xl font-bold text-azul-marino mb-3 text-center">Bienvenido {{ Auth::user()->name }}</h2>
    <p class="text-gray-700 text-center text-sm">Sistema Agrícola Lavalle</p>
    <p class="text-gray-600 text-center text-sm mt-2">Gestiona tus propiedades, cultivos y maquinarias.</p>
</div>
@endsection