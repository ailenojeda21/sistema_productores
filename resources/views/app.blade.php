<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="description" content="Sistema Agrícola Lavalle - Gestión de propiedades, cultivos y maquinaria">
    <meta name="theme-color" content="#223362">
    <link rel="shortcut icon" href="/public/favicon.png" type="image/png">
    <link rel="icon" href="/public/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="/public/favicon.png">

{{--    <title>Sistema Agr&iacute;cola Lavalle</title>--}}
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purpura: 'purple',
                    },
                },
            }
        }
    </script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
        @media (max-width: 768px) {
            body {
                font-size: 16px;
            }
        }
    </style>
    @yield('styles')
    @yield('scripts')

    <!-- Auto Logout on 419 Error (Mobile) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detect if device is mobile
            function isMobile() {
                return window.innerWidth < 768;
            }

            // Intercept fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args).then(response => {
                    // Check if response is 419 (Page Expired) and on mobile
                    if (response.status === 419 && isMobile()) {
                        handleAutoLogout();
                    }
                    return response;
                }).catch(error => {
                    throw error;
                });
            };

            // Intercept XMLHttpRequest
            const originalOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url, ...rest) {
                this.addEventListener('load', function() {
                    if (this.status === 419 && isMobile()) {
                        handleAutoLogout();
                    }
                });
                return originalOpen.apply(this, [method, url, ...rest]);
            };

            // Handle auto logout
            function handleAutoLogout() {
                // Check if logout form exists
                const logoutForm = document.querySelector('form[action*="logout"]');

                if (logoutForm) {
                    // Show notification
                    showNotification('Sesión expirada. Cerrando sesión automáticamente...');

                    // Submit logout form after a short delay
                    setTimeout(function() {
                        logoutForm.submit();
                    }, 1500);
                } else {
                    // Fallback: redirect to login
                    showNotification('Sesión expirada. Redirigiendo...');
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 1500);
                }
            }

            // Show notification
            function showNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 left-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded z-50 md:top-auto md:left-auto md:right-auto md:bottom-4 md:max-w-md md:mx-auto';
                notification.textContent = message;
                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(function() {
                    notification.remove();
                }, 5000);
            }

            // Listen for page visibility changes (user returns to tab)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden === false) {
                    // Page became visible, check if session is still valid
                    checkSessionValidity();
                }
            });

            // Check session validity
            function checkSessionValidity() {
                fetch('/api/check-session', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                }).catch(error => {
                    // Network error or 419, logout on mobile
                    if (isMobile()) {
                        handleAutoLogout();
                    }
                });
            }
        });
    </script>
</head>
<body class="font-sans antialiased bg-gray-100">
    @yield('content')
</body>

</html>
