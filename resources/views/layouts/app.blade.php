<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        <!-- Main menu -->
        <div id="main-menu" class="hidden md:flex md:items-center md:space-x-6 absolute md:static top-16 left-0 w-full md:w-auto bg-white md:bg-transparent shadow md:shadow-none z-40">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                @auth
                    <a href="{{ route('propiedades.index') }}" class="text-gray-700 hover:text-green-700 px-4 py-2 md:p-0">Propiedades</a>
                    <a href="{{ route('archivos.index') }}" class="text-gray-700 hover:text-blue-700 px-4 py-2 md:p-0">Archivos</a>
                    <a href="{{ route('maquinaria.index') }}" class="text-gray-700 hover:text-yellow-600 px-4 py-2 md:p-0">Maquinaria</a>
                    <a href="{{ route('implementos.index') }}" class="text-gray-700 hover:text-indigo-700 px-4 py-2 md:p-0">Implementos</a>
                    <a href="{{ route('cultivos.index') }}" class="text-gray-700 hover:text-purple-700 px-4 py-2 md:p-0">Cultivos</a>
                    <a href="{{ route('tecnologia_riego.index') }}" class="text-gray-700 hover:text-teal-700 px-4 py-2 md:p-0">Tecnología Riego</a>
                    @if (Route::has('profile.edit'))
    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-pink-700 flex items-center px-4 py-2 md:p-0"><span class="material-icons align-middle mr-1" style="font-size:18px;">person</span> Perfil</a>
@endif
                    <a href="#" class="text-gray-700 hover:text-orange-700 flex items-center px-4 py-2 md:p-0"><span class="material-icons align-middle mr-1" style="font-size:18px;">notifications</span> Notificaciones</a>
                @endauth
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 border-t md:border-t-0 pt-2 md:pt-0 mt-2 md:mt-0">
                @auth
                    <span class="text-gray-600 px-4 py-2 md:p-0">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2 md:p-0">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 w-full md:w-auto">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline px-4 py-2 md:p-0">Ingresar</a>
                    <a href="{{ route('register') }}" class="text-blue-700 font-semibold hover:underline px-4 py-2 md:p-0">Registrarse</a>
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
    <main class="pt-6">
        @yield('content')
    </main>
</body>
</html>
