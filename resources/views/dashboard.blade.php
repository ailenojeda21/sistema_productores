@extends('layouts.dashboard')

@section('dashboard-content')
    <x-breadcrumb />
    
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 md:p-8 w-full max-w-2xl flex flex-col items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-32 sm:h-40 md:h-48 mb-4 sm:mb-6 w-auto">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-azul-marino mb-3 sm:mb-4 text-center">Bienvenido al sistema agrícola Lavalle</h2>
        <p class="text-sm sm:text-base text-gray-700 text-center">Aquí podrás gestionar tus propiedades, cultivos, maquinarias y más.</p>
    </div>
@endsection