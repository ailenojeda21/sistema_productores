<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @yield('styles')
    @yield('scripts')
</head>
<body class="font-sans antialiased bg-gray-100">
    <header class="bg-white shadow sticky top-0 z-50">
        <nav class="container mx-auto flex items-center justify-between py-3 px-4">
            <div class="flex items-center space-x-6">
                <a href="/dashboard" class="font-bold text-green-700 text-xl">🌱 Sistema Agrícola</a>
            </div>
            
            <!-- Hamburger button (mobile) -->
            <button id="menu-toggle" class="md:hidden text-gray-700 focus:outline-none" aria-label="Abrir menú">
                <span class="material-icons" style="font-size:32px;">menu</span>
            </button>
            
            <!-- Desktop menu (hidden on mobile) -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Salir</button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">Ingresar</a>
                        <a href="{{ route('register') }}" class="text-blue-700 font-semibold hover:underline">Registrarse</a>
                    </div>
                @endauth
            </div>
        </nav>
        
        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
            <div class="container mx-auto px-4 py-2">
                @auth
                    <a href="#" class="block py-2 text-gray-700 hover:text-green-700">
                        <span class="material-icons align-middle mr-2">person</span> Perfil
                    </a>
                    <a href="#" class="block py-2 text-gray-700 hover:text-green-700">
                        <span class="material-icons align-middle mr-2">notifications</span> Notificaciones
                    </a>
                    <div class="border-t my-2"></div>
                    <span class="block py-2 text-gray-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-red-500 hover:text-red-700">
                            <span class="material-icons align-middle mr-2">logout</span> Salir
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-green-700">
                        <span class="material-icons align-middle mr-2">login</span> Ingresar
                    </a>
                    <a href="{{ route('register') }}" class="block py-2 text-gray-700 hover:text-blue-700">
                        <span class="material-icons align-middle mr-2">person_add</span> Registrarse
                    </a>
                @endauth
            </div>
        </div>
  
        <script>
            const toggleBtn = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            toggleBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (!toggleBtn.contains(e.target) && !mobileMenu.contains(e.target) && window.innerWidth < 768) {
                    mobileMenu.classList.add('hidden');
                }
            });
        </script>
    </header>
    
    <main class="">
        @yield('content')
    </main>
</body>
</html>