@props(['title', 'subtitle', 'loginRoute' => route('login'), 'registerRoute' => route('register'), 'forgotPasswordRoute' => route('password.request')])

<div class="min-h-screen w-full flex flex-col bg-white md:hidden">
    <!-- Fondo con superposición -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-black/40"></div>
        <img src="{{ asset('images/fruta2.png') }}" alt="Fondo agrícola" class="w-full h-full object-cover">
    </div>

    <!-- Contenido -->
    <div class="flex-1 flex flex-col p-6 relative z-10">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto">
        </div>

        <!-- Contenido principal -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-xl">
            <!-- Título -->
            <h1 class="text-2xl font-bold text-azul-marino mb-4 text-center leading-tight">
                {{ $title }}
            </h1>

            <!-- Subtítulo -->
            <p class="text-gray-600 text-center mb-8">
                {{ $subtitle }}
            </p>

            {{-- Slot para contenido adicional --}}
            {{ $slot }}

            <!-- Botón de Acceso -->
            <div class="mb-4">
                <a href="{{ $loginRoute }}" 
                   class="block w-full px-6 py-4 bg-gradient-to-r from-naranja-oscuro to-amber-700 text-white rounded-xl 
                          font-semibold text-center transition-all duration-300 transform active:scale-95">
                    Iniciar Sesión
                </a>
            </div>

            <!-- Divisor -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-white text-sm text-gray-500">¿Nuevo en la plataforma?</span>
                </div>
            </div>

            <!-- Botón de Registro -->
            <div class="mb-6">
                <a href="{{ $registerRoute }}" 
                   class="block w-full px-6 py-3.5 bg-white text-azul-marino rounded-xl font-medium text-center 
                          border-2 border-azul-marino active:bg-azul-marino active:text-white 
                          transition-all duration-200">
                    Crear Cuenta
                </a>
            </div>

            <!-- Enlace de recuperación -->
            <div class="text-center">
                <a href="{{ $forgotPasswordRoute }}" 
                   class="text-sm text-gray-500 active:text-azul-marino transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>
    </div>
</div>
