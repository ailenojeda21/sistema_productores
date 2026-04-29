@extends('layouts.dashboard')

@section('dashboard-content')

<!-- Desktop View -->
<div class="hidden lg:flex flex-col h-full w-full p-6">
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-32 mb-4 mx-auto">

        <h2 class="text-2xl font-bold text-azul-marino mb-4 text-center">
            Bienvenido {{ Auth::user()->name }} a <span class="tracking-[0.2em]">RUPAL</span>
        </h2>

        <p class="text-gray-600 text-center max-w-xl mx-auto">
            Gestiona tus propiedades, cultivos, maquinarias y comercialización desde aquí.
        </p>
    </div>

   
</div>

<!-- Mobile View -->
<div class="lg:hidden p-4 space-y-4">

    <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 mb-3">

        <h2 class="text-xl font-bold text-azul-marino text-center">
            Bienvenido {{ Auth::user()->name }} a <span class="tracking-[0.2em]">RUPAL</span>
        </h2>
    </div>


   
</div>

@endsection