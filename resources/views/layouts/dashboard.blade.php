@extends('layouts.app')

@section('content')
<div class="min-h-screen min-w-screen flex flex-col md:flex-row bg-gray-100" id="dashboard-container">
    <!-- Mobile Header with Hamburger Menu -->
    <div class="md:hidden fixed top-0 left-0 right-0 bg-azul-marino text-white flex items-center justify-between px-4 py-3 shadow-lg z-40">
        <span class="font-bold text-lg">Panel</span>
        <button id="drawer-toggle" class="p-2 hover:bg-opacity-80 rounded transition" aria-label="Toggle menu">
            <i class="nf nf-fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Drawer Overlay (Mobile) -->

    <!-- Sidebar/Drawer -->
    <aside id="drawer" class="fixed md:static top-0 left-0 h-screen md:h-auto w-64 bg-azul-marino text-white flex flex-col py-4 md:py-8 px-4 shadow-lg md:min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 md:z-auto">
        <div class="flex flex-col items-center mb-6 md:mb-10">
            <!-- Contenedor circular con borde y sombra -->
            <x-mini-avatar :user="auth()->user()" />
            <span class="font-bold text-base md:text-lg mt-2">Panel</span>
        </div>
        <nav class="flex flex-col space-y-1 md:space-y-2 overflow-y-auto">
            <!-- Inicio -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-home mr-2"></i>
                <span class="text-white sm:inline">Inicio</span>
            </a>

            <!-- Perfil -->
            <a href="{{ route('profile') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-user mr-2"></i>
                <span class="text-white sm:inline">Perfil</span>
            </a>

            <!-- Propiedades -->
            <a href="{{ route('propiedades.index') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-home mr-2"></i>
                <span class="text-white sm:inline">Propiedades</span>
            </a>

            <!-- Cultivos -->
            <a href="{{ route('cultivos.index') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-leaf mr-2"></i>
                <span class="text-white sm:inline">Cultivos</span>
            </a>

            <!-- Maquinarias -->
            <a href="{{ route('maquinaria.index') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-wrench mr-2"></i>
                <span class="text-white sm:inline">Maquinarias</span>
            </a>
            <!-- Comercios -->
            <a href="{{ route('comercios.index') }}" class="flex items-center px-3 md:px-4 py-2 rounded hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold text-base md:text-lg text-white">
                <i class="nf nf-fa-shopping_cart mr-2"></i>
                <span class="text-white sm:inline">Comercialización</span>
            </a>
            <!-- Cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-4 border-t border-white border-opacity-20">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-3 md:px-4 py-2 rounded hover:bg-red-500 hover:text-white transition font-semibold text-red-50 text-base md:text-lg">
                    <i class="nf nf-fa-sign_out mr-2"></i>
                    <span class="text-red-400 sm:inline">Cerrar sesión</span>
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main Panel -->
    <main class="flex-1 p-4 md:p-8 flex flex-col justify-start items-center overflow-y-auto text-base w-full mt-16 md:mt-0">
        @yield('dashboard-content')
    </main>
</div>

<!-- Drawer Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const drawerToggle = document.getElementById('drawer-toggle');
    const drawer = document.getElementById('drawer');
    const overlay = document.getElementById('drawer-overlay');
    const dashboardContainer = document.getElementById('dashboard-container');

    function toggleDrawer() {
        const isOpen = drawer.classList.contains('translate-x-0');

        if (isOpen) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    function openDrawer() {
        drawer.classList.remove('-translate-x-full');
        drawer.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.add('-translate-x-full');
        drawer.classList.remove('translate-x-0');
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Toggle button click
    drawerToggle.addEventListener('click', toggleDrawer);

    // Overlay click to close
    overlay.addEventListener('click', closeDrawer);

    // Close drawer when a link is clicked
    const drawerLinks = drawer.querySelectorAll('a, button');
    drawerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't close if it's a form submit button
            if (this.tagName === 'BUTTON' && this.closest('form')) {
                // Allow form submission, don't close drawer
                return;
            }
            // Close drawer for regular links
            if (this.tagName === 'A') {
                closeDrawer();
            }
        });
    });

    // Close drawer on window resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeDrawer();
        }
    });
});
</script>

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

/* Drawer animations */
@media (max-width: 767px) {
    #drawer {
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
    }

    #drawer.translate-x-0 {
        transform: translateX(0);
    }

    #drawer.-translate-x-full {
        transform: translateX(-100%);
    }
}

/* Prevent body scroll when drawer is open */
body.drawer-open {
    overflow: hidden;
}

</style>
