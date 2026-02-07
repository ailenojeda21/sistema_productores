@extends('layouts.app')

@section('content')
<div class="min-h-dvh bg-gray-100">
    <!-- Overlay (solo mobile) -->
    <div id="drawer-overlay"
         class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Mobile header -->
    <header class="lg:hidden sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-gray-200">
        <div class="h-14 px-4 flex items-center justify-between">
            <button id="mobile-menu-btn"
                    type="button"
                    class="bg-azul-marino text-white w-10 h-10 rounded-md shadow flex items-center justify-center
                           hover:bg-amarillo-claro hover:text-azul-marino transition-colors"
                    aria-label="Abrir menú">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <div class="text-sm font-semibold text-gray-700 truncate">
                {{ Auth::user()->name }}
            </div>

            <div class="w-10 h-10"></div>
        </div>
    </header>

    <div class="flex min-h-dvh">
        <!-- Sidebar (desktop) -->
        <aside class="hidden lg:flex w-64 bg-azul-marino text-white flex-col py-6 px-3 shadow-lg
                      sticky top-0 h-dvh">
            <div class="flex-shrink-0">
                <x-user-avatar :user="Auth::user()" size="md" :gradient="false" :showName="false" :thin-border="true" class="mb-0" />
                <p class="text-white text-sm text-center mt-2 mb-4 leading-tight font-semibold">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <nav class="flex-1 space-y-1 pr-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">dashboard</span> Inicio
                </a>
                <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">person</span> Perfil
                </a>
                <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">home</span> Propiedades
                </a>
                <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-3">spa</span> Cultivos
                </a>
                <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">agriculture</span> Maquinarias
                </a>
                <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">shopping_cart</span> Comercialización
                </a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="pt-3 flex-shrink-0">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-3 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-300 text-base">
                    <span class="material-symbols-outlined mr-2">logout</span> Cerrar sesión
                </button>
            </form>
        </aside>

        <!-- Drawer (mobile) -->
        <aside id="drawer"
               class="fixed lg:hidden inset-y-0 left-0 z-50 bg-azul-marino text-white shadow-lg
                      w-[85vw] max-w-xs transform -translate-x-full transition-transform duration-300
                      h-dvh flex flex-col py-4 px-3">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="w-10"></div>
                    <x-user-avatar :user="Auth::user()" size="lg" :gradient="false" :showName="false" :thin-border="true" class="mx-auto" />
                    <button id="drawer-close"
                            type="button"
                            class="w-10 h-10 rounded-md grid place-items-center hover:bg-white/10"
                            aria-label="Cerrar menú">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <p class="text-white text-sm text-center mt-2 mb-3 font-semibold truncate">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <nav class="flex-1 overflow-y-auto space-y-1 pr-1 scrollbar-hide">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">dashboard</span> Inicio
                </a>
                <a href="{{ route('profile') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">person</span> Perfil
                </a>
                <a href="{{ route('propiedades.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">home</span> Propiedades
                </a>
                <a href="{{ route('cultivos.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">spa</span> Cultivos
                </a>
                <a href="{{ route('maquinaria.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">agriculture</span> Maquinarias
                </a>
                <a href="{{ route('comercios.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">shopping_cart</span> Comercialización
                </a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="pt-2 flex-shrink-0">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-3 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-300">
                    <span class="material-symbols-outlined mr-2 text-xl">logout</span> Cerrar sesión
                </button>
            </form>
        </aside>

        <!-- Main -->
        <main class="flex-1 min-w-0">
            <div class="p-4 lg:p-8">
                <div class="w-full max-w-7xl mx-auto">
                    @yield('dashboard-content')
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('drawer-close');

    function openDrawer() {
        drawer.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.documentElement.classList.add('overflow-hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDrawer() {
        drawer.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.documentElement.classList.remove('overflow-hidden');
        document.body.classList.remove('overflow-hidden');
    }

    menuBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);

    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Cierra al navegar (mejora UX)
    drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
</script>
@endsection

@section('styles')
<style>
  table { border-collapse: collapse; width: 100%; }
  td, th { word-wrap: break-word; white-space: normal; }
  
  /* Ocultar scrollbar pero mantener funcionalidad */
  .scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }
  .scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Chrome, Safari and Opera */
  }
</style>
@endsection
