<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Registro Agrícola') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header >
            <nav class="container mx-auto flex items-center justify-between py-3 px-4">
                <div class="flex items-center space-x-6">
                    <a href="/dashboard" class="font-bold text-white text-xl flex items-center">
                        <i class="material-icons mr-2" style="font-size:24px;">apps</i>
                        Sistema Agrícola SAP
                    </a>
                </div>
                <!-- Hamburger button (mobile) -->
                <button id="menu-toggle" class="md:hidden text-white focus:outline-none rounded p-1 hover:bg-opacity-10 hover:bg-white" aria-label="Abrir menú">
                    <span class="material-icons" style="font-size:28px;">menu</span>
                </button>
                <!-- Main menu -->
                <div id="main-menu" class="hidden md:flex md:items-center md:space-x-2 absolute md:static top-16 left-0 w-full md:w-auto bg-white md:bg-transparent shadow md:shadow-none z-40">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-1">
                        @auth
                            <a href="{{ route('propiedades.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">home_work</i> Propiedades
                            </a>
                            <a href="{{ route('archivos.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">folder</i> Archivos
                            </a>
                            <a href="{{ route('maquinaria.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">agriculture</i> Maquinaria
                            </a>
                            <a href="{{ route('implementos.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">build</i> Implementos
                            </a>
                            <a href="{{ route('cultivos.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">eco</i> Cultivos
                            </a>
                            <a href="{{ route('tecnologia_riego.index') }}" class="sap-nav-link text-gray-700 md:text-white">
                                <i class="material-icons mr-2" style="font-size:18px;">water_drop</i> Tecnología Riego
                            </a>
                        @endauth
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 border-t md:border-t-0 pt-2 md:pt-0 mt-2 md:mt-0 md:ml-4">
                        @auth
                            <div class="px-4 py-2 md:p-0 flex items-center">
                                <i class="material-icons mr-2 text-white" style="font-size:18px;">person</i>
                                <span class="text-gray-600 md:text-white">{{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2 md:p-0">
                                @csrf
                                <button type="submit" class="sap-button-outline px-3 py-1 bg-white text-blue-600 rounded hover:bg-blue-50 w-full md:w-auto flex items-center">
                                    <i class="material-icons mr-1" style="font-size:16px;">logout</i> Salir
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="sap-button-outline bg-white text-blue-600 font-semibold hover:bg-blue-50 px-4 py-1 rounded">Ingresar</a>
                            <a href="{{ route('register') }}" class="sap-button bg-blue-700 text-white font-semibold hover:bg-blue-800 px-4 py-1 rounded">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </nav>
            <script>
                const toggleBtn = document.getElementById('menu-toggle');
                const menu = document.getElementById('main-menu');
                toggleBtn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
                // Opcional: Oculta el menú al hacer click fuera en móvil
                document.addEventListener('click', function(e) {
                    if (!toggleBtn.contains(e.target) && !menu.contains(e.target) && window.innerWidth < 768) {
                        menu.classList.add('hidden');
                    }
                });
            </script>
        </header>

        <main >
            @yield('content')
        </main>


        <footer >
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm">&copy; {{ date('Y') }} Sistema Registro Agrícola . Todos los derechos reservados.</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="text-sm text-gray-300 hover:text-white">Ayuda</a>
                        <a href="#" class="text-sm text-gray-300 hover:text-white">Privacidad</a>
                        <a href="#" class="text-sm text-gray-300 hover:text-white">Términos</a>
                    </div>
                </div>
            </div>
        </footer>
    <div>
</body>
</html>
