@extends('layouts.app')

@section('desktop-content')
<div class="h-full flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg h-full overflow-y-auto">
        <x-user-avatar :user="Auth::user()" size="md" :gradient="false" :showName="true" class="mb-4" />
        
        <nav class="flex flex-col space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">dashboard</span>
                Dashboard
            </a>

            <!-- Perfil -->
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">person</span>
                Perfil
            </a>

            <!-- Propiedades -->
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">home</span>
                Propiedades
            </a>

            <!-- Cultivos -->
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">agriculture</span>
                Cultivos
            </a>

            <!-- Maquinarias -->
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">precision_manufacturing</span>
                Maquinarias
            </a>

            <!-- Comercios -->
            <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">shopping_cart</span>
                Comercialización
            </a>

            <!-- Cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" 
                    class="w-full flex items-center px-4 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-400">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>
    
    <!-- Main Panel -->
    <main class="flex-1 p-8 overflow-y-auto text-base">
        <div class="w-full max-w-7xl mx-auto">
            @yield('dashboard-content')
        </div>
    </main>
</div>
@endsection

@section('mobile-content')
<div class="h-full flex flex-col bg-gray-100">
    <!-- Overlay for mobile -->
    <div id="drawer-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
    
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="fixed top-4 left-4 z-50 bg-azul-marino text-white p-2 rounded-md shadow-lg">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <!-- Sidebar Drawer -->
    <aside id="drawer" class="fixed w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg h-full z-50 transition-transform duration-300 -translate-x-full overflow-y-auto">
        <x-user-avatar :user="Auth::user()" size="md" :gradient="false" :showName="true" class="mb-4" />
        
        <nav class="flex flex-col space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">dashboard</span>
                Dashboard
            </a>

            <!-- Perfil -->
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">person</span>
                Perfil
            </a>

            <!-- Propiedades -->
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">home</span>
                Propiedades
            </a>

            <!-- Cultivos -->
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">agriculture</span>
                Cultivos
            </a>

            <!-- Maquinarias -->
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">precision_manufacturing</span>
                Maquinarias
            </a>

            <!-- Comercios -->
            <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                <span class="material-symbols-outlined mr-3">shopping_cart</span>
                Comercialización
            </a>

            <!-- Cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" 
                    class="w-full flex items-center px-4 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-400">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>
    
    <!-- Main Panel -->
    <main class="flex-1 pt-16 p-4 overflow-y-auto text-base">
        @yield('dashboard-content')
    </main>
</div>

<script>
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');
    const menuBtn = document.getElementById('mobile-menu-btn');

    menuBtn.addEventListener('click', () => {
        drawer.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', () => {
        drawer.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>
@endsection

@section('styles')
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
@endsection
