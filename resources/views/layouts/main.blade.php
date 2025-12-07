@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gray-100 " >
    <!-- Sidebar -->
    <aside class=" w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg">
      <div class="flex flex-col items-center mb-10">
    <!-- Contenedor circular -->
    <div class="bg-blue-50 rounded-full p-0 shadow-md overflow-hidden h-24 w-24">
    <img
        src="{{ Auth::user()->avatar ? asset('images/avatars/' . Auth::user()->avatar) : asset('images/avatars/uno.png') }}"
        alt="Avatar"
        class="h-full w-full object-cover"
    >
</div>


    <span class="font-bold text-lg">Panel</span>
</div>
        <nav class="flex flex-col space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fa-icon fas fa-home"></i>
                Dashboard
            </a>

            <!-- Perfil -->
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fa-icon fas fa-user"></i>
                Perfil
            </a>

            <!-- Propiedades -->
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fas fa-building"></i>
                Propiedades
            </a>

            <!-- Cultivos -->
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fas fa-seedling mr-2"></i>
                Cultivos
            </a>

            <!-- Maquinarias -->
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fas fa-tools mr-2"></i>
                Maquinarias
            </a>
            <!-- Comercios -->
            <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <i class="fas fa-store mr-2"></i>
                Comercialización
            </a>
        <!-- Cerrar sesión -->
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-400">
                <i class="fa-icon fas fa-sign-out-alt"></i>
                Cerrar sesión
            </button>
        </form>
        </nav>
    </aside>
    <!-- Main Panel -->
    <main class="flex-1 p-8 overflow-y-auto text-base">
        <!-- Breadcrumb -->
        <div class="w-full max-w-7xl mx-auto mb-6">
            <x-breadcrumb />
        </div>

        <!-- Contenido principal -->
        <div class="w-full max-w-7xl mx-auto">
            @yield('dashboard-content')
        </div>
    </main>
</div>
@endsection
<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;

}

table {
  border-collapse: collapse;
  width: 100%;
}

td, th {
  word-wrap: break-word;
  white-space: normal;
}

</style>
