@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex bg-gray-100 overflow-hidden border-1 border-red-400">
        <!-- Sidebar (drawer en móvil) -->
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 w-64 bg-azul-marino text-white flex flex-col py-8 px-4 shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out z-40 md:relative md:translate-x-0 md:shadow-none md:flex">
            <div class="flex flex-col items-center mb-10">
                <!-- Contenedor circular -->
                <div class="border-white border-3 bg-blue-50 rounded-full p-0 shadow-md overflow-hidden h-24 w-24">
                    <img
                        src="{{ Auth::user()->avatar ? asset('images/avatars/' . Auth::user()->avatar) : asset('images/avatars/uno.png') }}"
                        alt="Avatar"
                        class="h-full w-full object-cover "
                    >
                </div>


                <span class="font-bold text-lg">Panel</span>
            </div>
            <!-- Botón cerrar drawer (visible solo en móvil) -->
            <button id="drawer-close" class="absolute top-4 right-4 text-white md:hidden" aria-label="Cerrar menú">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <nav class="flex flex-col space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fa-icon fas fa-home"></i>
                    Dashboard
                </a>

                <!-- Perfil -->
                <a href="{{ route('profile') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fa-icon fas fa-user"></i>
                    Perfil
                </a>

                <!-- Propiedades -->
                <a href="{{ route('propiedades.index') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fas fa-building mr-2"></i>
                    Propiedades
                </a>

                <!-- Cultivos -->
                <a href="{{ route('cultivos.index') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fas fa-seedling mr-2"></i>
                    Cultivos
                </a>

                <!-- Maquinarias -->
                <a href="{{ route('maquinaria.index') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fas fa-tools mr-2"></i>
                    Maquinarias
                </a>
                <!-- Comercios -->
                <a href="{{ route('comercios.index') }}"
                   class="flex items-center px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-lg">
                    <i class="fas fa-store mr-2"></i>
                    Comercialización
                </a>
                <hr>

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
        <!-- Backdrop para el drawer en móvil -->
        <div id="drawer-backdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>
        <!-- Main Panel -->
        <main class="flex-1 p-8 overflow-y-auto text-base">
            <!-- Botón abrir drawer (solo móvil) -->
            <div class="md:hidden mb-4">
                <button id="drawer-open" class="p-2 bg-white rounded shadow cursor-pointer" style="position:fixed;top:1rem;left:1rem;z-index:9999;" aria-label="Abrir menú" aria-expanded="false" tabindex="0">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <!-- Breadcrumb -->
            <div class="w-full max-w-7xl mx-auto mb-6">
                <x-breadcrumb/>
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

<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            console.log('[drawer] DOMContentLoaded');
            const openBtn = document.getElementById('drawer-open');
            const closeBtn = document.getElementById('drawer-close');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('drawer-backdrop');

            console.log('[drawer] elements:', { openBtn, closeBtn, sidebar, backdrop });

            if (!sidebar) {
                console.warn('[drawer] sidebar not found, aborting');
                return;
            }

            // ensure a smooth inline transition regardless of Tailwind purge
            sidebar.style.transition = sidebar.style.transition || 'transform 300ms ease-in-out';

            function isMobile() {
                return window.innerWidth < 768; // md breakpoint
            }

            function openDrawer() {
                if (!isMobile()) return;
                sidebar.style.transform = 'translateX(0)';
                if (backdrop) backdrop.classList.remove('hidden');
                if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
                document.body.classList.add('overflow-hidden');
            }

            function closeDrawer() {
                if (!isMobile()) return;
                sidebar.style.transform = 'translateX(-100%)';
                if (backdrop) backdrop.classList.add('hidden');
                if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
            }

            // Ensure correct state on load
            if (isMobile()) {
                sidebar.style.transform = 'translateX(-100%)';
            } else {
                sidebar.style.transform = '';
                if (backdrop) backdrop.classList.add('hidden');
            }

            if (openBtn) {
                openBtn.addEventListener('click', function (e) {
                    console.log('[drawer] open clicked');
                    openDrawer();
                });
                // also support keyboard activation
                openBtn.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        openDrawer();
                    }
                });
            } else console.warn('[drawer] openBtn not found');

            if (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    console.log('[drawer] close clicked');
                    closeDrawer();
                });
            } else console.warn('[drawer] closeBtn not found');

            if (backdrop) {
                backdrop.addEventListener('click', function (e) {
                    console.log('[drawer] backdrop clicked');
                    closeDrawer();
                });
            } else console.warn('[drawer] backdrop not found');

            // Cerrar con ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDrawer();
            });

            // Reset when resizing to desktop to avoid stuck inline styles
            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    sidebar.style.transform = '';
                    if (backdrop) backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
                } else {
                    // if mobile and sidebar hasn't been opened, keep it hidden
                    if (!sidebar.classList.contains('translate-x-0')) {
                        sidebar.style.transform = 'translateX(-100%)';
                    }
                }
            });
        });
    })();
</script>
