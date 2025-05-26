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
            @include('partials.navbar')
            {{-- <script>
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
            </script> --}}
        </header>

        <main >
            @yield('content')
                </main>


       @include('partials.footer')
    <div>
</body>
</html>
