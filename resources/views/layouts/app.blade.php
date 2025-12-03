<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Nerd Fonts -->
    <link rel="stylesheet" href="https://www.nerdfonts.com/assets/css/webfont.css">
    <style>
        .nf {
            font-family: 'NerdFontsSymbols Nerd Font', 'Symbols Nerd Font', 'SymbolsNerdFont', sans-serif;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @yield('styles')
    @yield('scripts')
</head>
<body class="font-sans antialiased bg-gray-100 w-full h-full min-h-screen overflow-hidden">
    <main class="">
        @yield('content')
    </main>
</body>
</html>