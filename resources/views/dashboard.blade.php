@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl flex flex-col items-center">
       <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-48 mb-6">
        <h2 class="text-2xl font-bold text-azul-marino mb-4">Bienvenido al sistema agrícola Lavalle</h2>
        <p class="text-gray-700">Aquí podrás gestionar tus propiedades, cultivos, maquinarias y más.</p>
    </div>
@endsection