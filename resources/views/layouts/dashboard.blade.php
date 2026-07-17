@extends('layouts.app')

@section('content')
<div class="min-h-dvh bg-[#F5F5F7]">
    <!-- Overlay (solo mobile) -->
    <div id="drawer-overlay"
         class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Mobile header -->
    <header class="lg:hidden sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-gray-200">
        <div class="h-14 px-4 flex items-center justify-between">
            <button id="mobile-menu-btn"
                    type="button"
                    class="bg-naranja-oscuro text-white w-10 h-10 rounded-md shadow flex items-center justify-center
                           hover:bg-[#FFD166] hover:text-naranja-oscuro transition-colors"
                    aria-label="Abrir menú">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <div class="text-sm font-semibold text-gray-700 truncate">
                {{ Auth::user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0" id="logout-form-mobile">
    @csrf
    <button type="button" onclick="confirmLogout('logout-form-mobile')"
            class="flex items-center justify-center w-10 h-10 rounded-md text-red-500 hover:bg-red-500 hover:text-white transition border border-red-500"
            aria-label="Cerrar sesión">
        <span class="material-symbols-outlined">logout</span>
    </button>
</form>
        </div>
    </header>

    <div class="flex min-h-dvh">
        <!-- Sidebar (desktop) -->
        <aside class="hidden lg:flex w-64 bg-naranja-oscuro text-white flex-col py-6 px-3 shadow-lg
                      sticky top-0 h-dvh">
            <div class="flex-shrink-0">
                <x-user-avatar :user="Auth::user()" size="md" :gradient="false" :showName="false" :thin-border="true" class="mb-0" />
                <p class="text-white text-sm text-center mt-2 mb-4 leading-tight font-semibold">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <nav class="flex-1 space-y-1 pr-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">dashboard</span> Inicio
                </a>
                <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">person</span> Perfil
                    <x-completion-circle :percentage="Auth::user()->profile_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('propiedades.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">home</span> Propiedades
                    <x-completion-circle :percentage="Auth::user()->propiedades_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('cultivos.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-3">spa</span> Cultivos
                    <x-completion-circle :percentage="Auth::user()->cultivos_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('maquinaria.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">agriculture</span> Maquinarias
                    <x-completion-circle :percentage="Auth::user()->maquinarias_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('comercios.index') }}" class="flex items-center px-4 py-3 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold text-[17px]">
                    <span class="material-symbols-outlined mr-2">shopping_cart</span> Comercialización
                    <x-completion-circle :percentage="Auth::user()->comercializacion_completeness" class="ml-auto" />
                </a>
            </nav>

            <div class="flex-shrink-0 pt-4 flex justify-center">
              <!-- 48px -->
<!-- 64px -->
    <img src="{{ asset('images/logo2.png') }}" alt="RUPAL" class="h-16 w-auto opacity-90">
            </div>
        </aside>

        <!-- Drawer (mobile) -->
        <aside id="drawer"
               class="fixed lg:hidden inset-y-0 left-0 z-50 bg-naranja-oscuro text-white shadow-lg
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
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">dashboard</span> Inicio
                </a>
                <a href="{{ route('profile') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">person</span> Perfil
                    <x-completion-circle :percentage="Auth::user()->profile_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('propiedades.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">home</span> Propiedades
                    <x-completion-circle :percentage="Auth::user()->propiedades_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('cultivos.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">spa</span> Cultivos
                    <x-completion-circle :percentage="Auth::user()->cultivos_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('maquinaria.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">agriculture</span> Maquinarias
                    <x-completion-circle :percentage="Auth::user()->maquinarias_completeness" class="ml-auto" />
                </a>
                <a href="{{ route('comercios.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-[#FFD166] hover:text-naranja-oscuro transition font-semibold">
                    <span class="material-symbols-outlined mr-2 text-xl">shopping_cart</span> Comercialización
                    <x-completion-circle :percentage="Auth::user()->comercializacion_completeness" class="ml-auto" />
                </a>
            </nav>

            <div class="flex-shrink-0 pt-4 flex justify-center">
                <img src="{{ asset('images/logo2.png') }}" alt="RUPAL" class="h-16 w-auto opacity-90">
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1 min-w-0 flex flex-col">

            <!-- 🔥 NAV SUPERIOR DESKTOP -->
            <header class="hidden lg:flex items-center justify-end h-12 px-6 border-b border-gray-200 bg-white">
                <div class="flex items-center gap-2 px-3 py-1 rounded-lg border bg-white shadow-sm">
                    <div class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-700">
                        {{ Auth::user()->name }}
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-3 flex h-8 items-center shrink-0" id="logout-form-desktop">
                    @csrf
                    <button type="button" onclick="confirmLogout('logout-form-desktop')"
                            class="inline-flex h-8 items-center justify-center gap-1 px-2 rounded-lg text-sm leading-none text-red-500 hover:bg-red-500 hover:text-white transition font-medium border border-red-500">
                        <span class="material-symbols-outlined text-base leading-none">logout</span> Cerrar sesión
                    </button>
                </form>
            </header>

            <div class="pt-4 pb-6 px-4 lg:px-8">
                <div class="w-full max-w-7xl mx-auto">
                    @yield('dashboard-content')
                </div>
            </div>
        </main>
    </div>
</div>

<div id="logout-dialog" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40" onclick="cancelLogout()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Cerrar sesión</h3>
        <p class="text-sm text-gray-600 mb-6">¿Estás seguro de que deseas cerrar la sesión?</p>
        <div class="flex gap-3 justify-end">
            <button class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition" onclick="cancelLogout()">Cancelar</button>
            <button class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition" onclick="submitLogout()">Cerrar sesión</button>
        </div>
    </div>
</div>

<script>
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('drawer-close');

    let pendingLogoutForm = null;

    function confirmLogout(formId) {
        pendingLogoutForm = document.getElementById(formId);
        document.getElementById('logout-dialog').classList.remove('hidden');
    }

    function cancelLogout() {
        pendingLogoutForm = null;
        document.getElementById('logout-dialog').classList.add('hidden');
    }

    function submitLogout() {
        if (pendingLogoutForm) {
            pendingLogoutForm.submit();
        }
        pendingLogoutForm = null;
        document.getElementById('logout-dialog').classList.add('hidden');
    }

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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeDrawer(); cancelLogout(); }
    });

    drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
</script>
@endsection

@section('styles')
<style>
  table { border-collapse: collapse; width: 100%; }
  td, th { word-wrap: break-word; white-space: normal; }

  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
</style>
@endsection
