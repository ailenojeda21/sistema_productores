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
<body class="font-sans antialiased bg-gray-100 w-full h-full min-h-screen overflow-hidden">
    <main class="">
        @yield('content')
    </main>
</body>
</html>