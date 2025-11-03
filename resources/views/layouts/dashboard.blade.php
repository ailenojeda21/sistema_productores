@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gray-100 " >
    <!-- Sidebar -->
    <aside class=" w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg">
        <div class="flex flex-col items-center mb-10">
            <!-- Ícono de usuario dentro de un círculo -->
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
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Velocímetro -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3C7.03 3 3 7.03 3 12c0 3.87 3.13 7 7 7h4c3.87 0 7-3.13 7-7 0-4.97-4.03-9-9-9zm0 13a2 2 0 110-4 2 2 0 010 4zm6.93-5.36a.75.75 0 00-1.06-1.06l-1.42 1.42a.75.75 0 001.06 1.06l1.42-1.42z"/>
                </svg>
                Dashboard
            </a>

            <!-- Perfil -->
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Persona -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM4 20v-1a4 4 0 014-4h8a4 4 0 014 4v1" />
                </svg>
                Perfil
            </a>

            <!-- Propiedades -->
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Casa -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4H9v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z" />
                </svg>
                Propiedades
            </a>

            <!-- Cultivos -->
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Planta con raíz -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 22V12m0 0s4-2 4-6a4 4 0 00-4-4 4 4 0 00-4 4c0 4 4 6 4 6zm0 0s-2 4-6 4a6 6 0 01-6-6 6 6 0 016 6h0" />
                </svg>
                Cultivos
            </a>

            <!-- Maquinarias -->
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Tractor -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 640 512" class="h-5 w-5 mr-2">
                    <path d="M624 352h-16V243.3c0-21.2-10.6-41.1-28.4-53.3L504 144V96c0-17.7-14.3-32-32-32h-32V32c0-17.7-14.3-32-32-32H368c-17.7 0-32 14.3-32 32v32h-16C302.3 64 288 78.3 288 96v96H64c-35.3 0-64 28.7-64 64v128c0 17.7 14.3 32 32 32h49.3C96.5 441.2 123.4 480 160 480s63.5-38.8 78.7-96H352v32c0 53 43 96 96 96s96-43 96-96v-32h80c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 432c-17.7 0-32-28.7-32-64s14.3-64 32-64 32 28.7 32 64-14.3 64-32 64zm288-64c0-35.3 28.7-64 64-64s64 28.7 64 64-28.7 64-64 64-64-28.7-64-64z"/>
                </svg>
                Maquinarias
            </a>
            <!-- Comercios -->
            <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <!-- Tienda -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-5 w-5 mr-2">
                    <path d="M4 4h16v2H4zm0 4h16v2H4zm0 4h16v10H4zm2 2v6h12v-6z"/>
                </svg>
                Comercialización
            </a>
        <!-- Cerrar sesión -->
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" 
                class="w-full flex items-center px-4 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-400">
                <!-- Ícono logout -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 
                        2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                </svg>
                Cerrar sesión
            </button>
        </form>
        </nav>
    </aside>
    <!-- Main Panel -->
    <main class="flex-1 p-8 flex flex-col justify-start items-center overflow-y-auto text-base">
        @yield('dashboard-content')
    </main>
</div>
@endsection
