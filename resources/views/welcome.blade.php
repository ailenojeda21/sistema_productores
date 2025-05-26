<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <i class="material-icons text-blue-600 mr-2" style="font-size:30px;">agriculture</i>
                <span class="font-bold text-xl text-gray-800">Sistema Agrícola SAP</span>
            </div>            <div class="hidden md:flex space-x-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary px-4 py-2 rounded-md font-medium flex items-center">
                            <i class="material-icons mr-1" style="font-size:18px;">dashboard</i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary px-4 py-2 rounded-md font-medium">Ingresar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-md font-medium">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
            <div class="md:hidden">
                <button id="mobileMenuBtn" class="text-blue-600">
                    <i class="material-icons" style="font-size:28px;">menu</i>
                </button>
                <div id="mobileMenu" class="hidden absolute top-16 right-4 bg-white shadow-lg rounded-md p-4 z-50">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block py-2 px-4 text-blue-600 font-medium hover:bg-blue-50 rounded-md mb-1 flex items-center">
                                <i class="material-icons mr-1" style="font-size:18px;">dashboard</i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block py-2 px-4 text-blue-600 font-medium hover:bg-blue-50 rounded-md mb-1">Ingresar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block py-2 px-4 bg-blue-600 text-white font-medium hover:bg-blue-700 rounded-md">Registrarse</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-pattern text-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Gestión Agrícola Inteligente</h1>
                    <p class="text-xl mb-8 text-blue-100">Sistema completo para productores agrícolas que facilita la administración de propiedades, cultivos, maquinaria y más.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 py-3 rounded-md font-bold flex items-center">
                            <i class="material-icons mr-2" style="font-size:20px;">how_to_reg</i> Comenzar ahora
                        </a>
                        <a href="#features" class="bg-transparent border border-white text-white px-6 py-3 rounded-md font-medium flex items-center">
                            <i class="material-icons mr-2" style="font-size:20px;">info</i> Más información
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="https://via.placeholder.com/600x400/0A6ED1/FFFFFF?text=Sistema+Agricola+SAP" alt="Sistema Agrícola" class="rounded-lg shadow-xl max-w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Características principales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">home_work</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Gestión de Propiedades</h3>
                    <p class="text-gray-600">Administre múltiples propiedades agrícolas con información detallada de ubicación y características.</p>
                </div>
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">eco</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Control de Cultivos</h3>
                    <p class="text-gray-600">Seguimiento detallado de todos sus cultivos, áreas sembradas y estacionalidad.</p>
                </div>
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">agriculture</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Inventario de Maquinaria</h3>
                    <p class="text-gray-600">Gestione toda su maquinaria agrícola e implementos asociados de manera eficiente.</p>
                </div>
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">water_drop</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Tecnología de Riego</h3>
                    <p class="text-gray-600">Administración de sistemas de riego implementados en sus propiedades.</p>
                </div>
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">folder</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Gestión Documental</h3>
                    <p class="text-gray-600">Almacene y organice todos los documentos relacionados con su operación agrícola.</p>
                </div>
                <div class="feature-card p-6 rounded-lg bg-white shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="material-icons text-blue-600" style="font-size:28px;">devices</i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Acceso Multiplataforma</h3>
                    <p class="text-gray-600">Acceda a su información desde cualquier dispositivo con diseño totalmente responsivo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">¿Listo para optimizar la gestión de su producción agrícola?</h2>
            <p class="text-xl mb-8 text-gray-600 max-w-3xl mx-auto">Únase a cientos de productores que ya están utilizando nuestro sistema para mejorar la eficiencia de sus operaciones.</p>
            <a href="{{ route('register') }}" class="btn-primary px-8 py-3 rounded-md font-bold inline-flex items-center">
                <i class="material-icons mr-2" style="font-size:20px;">rocket_launch</i> Comenzar ahora
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="material-icons text-blue-400 mr-2" style="font-size:24px;">agriculture</i>
                        <span class="font-bold text-lg">Sistema Agrícola SAP</span>
                    </div>
                    <p class="text-gray-400">Solución integral para la gestión de operaciones agrícolas.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Enlaces rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Iniciar sesión</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Registrarse</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white">Características</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="material-icons mr-2" style="font-size:18px;">email</i> info@sistemaagricola.com
                        </li>
                        <li class="flex items-center">
                            <i class="material-icons mr-2" style="font-size:18px;">phone</i> +123 456 7890
                        </li>
                        <li class="flex items-center">
                            <i class="material-icons mr-2" style="font-size:18px;">location_on</i> Ciudad Agrícola, País
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="material-icons" style="font-size:18px;">facebook</i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="material-icons" style="font-size:18px;">twitter</i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="material-icons" style="font-size:18px;">youtube_activity</i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-blue-800 transition-colors">
                            <i class="material-icons" style="font-size:18px;">connect_without_contact</i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Sistema Agrícola SAP. Todos los derechos reservados.</p>
            </div>        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close the menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }

            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop,
                            behavior: 'smooth'
                        });

                        // Close mobile menu if open
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>
