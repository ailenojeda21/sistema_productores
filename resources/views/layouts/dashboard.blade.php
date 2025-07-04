@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg">
        <div class="flex flex-col items-center mb-10">
   <!-- Heroicon: user -->
<div class="bg-blue-50 rounded-full p-4 shadow-md">
  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
       class="h-16 w-16 text-amarillo-claro">
    <path fill-rule="evenodd"
          d="M12 2a5 5 0 100 10 5 5 0 000-10zm0 12c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"
          clip-rule="evenodd" />
  </svg>
</div>



            <span class="font-bold text-lg">Panel</span>
        </div>
        <nav class="flex flex-col space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6" /></svg>
                Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Perfil
            </a>
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Propiedades
            </a>
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 7v7m0 0H9m3 0h3" /></svg>
                Cultivos
            </a>
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6" /></svg>
                Maquinarias
            </a>
        </nav>
    </aside>
    <!-- Main Panel -->
<main class="flex-1 p-8 flex flex-col justify-start items-center">

    
        @yield('dashboard-content')
    </main>
</div>
@endsection
