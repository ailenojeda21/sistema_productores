<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
     <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            purpura: 'purple',
                        },
                    },
                },
            }
        </script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    @yield('scripts')    
</head>
<body class="font-sans antialiased bg-gray-100">
    @yield('content')
</body>

</html>
