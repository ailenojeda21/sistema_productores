@extends('layouts.dashboard')

@section('dashboard-content')
<!-- Desktop View -->
<div class="hidden lg:flex flex-col h-full w-full p-6">
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-32 mb-4 mx-auto">

        <h2 class="text-2xl font-bold text-azul-marino mb-4 text-center">
            Bienvenido {{ Auth::user()->name }} a <span class="tracking-[0.2em]">RUPAL</span>
        </h2>

        <p class="text-gray-600 text-center max-w-xl mx-auto">
            Gestiona tus propiedades, cultivos, maquinarias y comercializacion desde aqui.
        </p>
    </div>

    <!-- Modulos -->
    <div class="grid grid-cols-5 gap-4">
        <!-- Perfil -->
        <a href="{{ route('profile') }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-[0_0_12px_#F5B41033] hover:shadow-[0_0_20px_#F5B41055] transition-all duration-300 group flex flex-col items-center text-center">
            <div class="relative w-20 h-20 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($profileCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($profileCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">{{ $profileCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($profileCompleteness ?? 0) == 100)
                <span class="text-xs text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-lg font-bold mt-1" style="color: #223362">Perfil</h3>
        </a>

        <!-- Propiedades -->
        <a href="{{ route('propiedades.index') }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-[0_0_12px_#F5B41033] hover:shadow-[0_0_20px_#F5B41055] transition-all duration-300 group flex flex-col items-center text-center">
            <div class="relative w-20 h-20 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($propiedadesCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($propiedadesCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">{{ $propiedadesCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($propiedadesCompleteness ?? 0) == 100)
                <span class="text-xs text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-lg font-bold mt-1" style="color: #223362">Propiedades</h3>
        </a>

        <!-- Cultivos -->
        <a href="{{ route('cultivos.index') }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-[0_0_12px_#F5B41033] hover:shadow-[0_0_20px_#F5B41055] transition-all duration-300 group flex flex-col items-center text-center">
            <div class="relative w-20 h-20 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($cultivosCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($cultivosCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">{{ $cultivosCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($cultivosCompleteness ?? 0) == 100)
                <span class="text-xs text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-lg font-bold mt-1" style="color: #223362">Cultivos</h3>
        </a>

        <!-- Maquinarias -->
        <a href="{{ route('maquinaria.index') }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-[0_0_12px_#F5B41033] hover:shadow-[0_0_20px_#F5B41055] transition-all duration-300 group flex flex-col items-center text-center">
            <div class="relative w-20 h-20 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($maquinariasCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($maquinariasCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">{{ $maquinariasCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($maquinariasCompleteness ?? 0) == 100)
                <span class="text-xs text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-lg font-bold mt-1" style="color: #223362">Maquinarias</h3>
        </a>

        <!-- Comercializacion -->
        <a href="{{ route('comercios.index') }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-[0_0_12px_#F5B41033] hover:shadow-[0_0_20px_#F5B41055] transition-all duration-300 group flex flex-col items-center text-center">
            <div class="relative w-20 h-20 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($comercializacionCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($comercializacionCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">{{ $comercializacionCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($comercializacionCompleteness ?? 0) == 100)
                <span class="text-xs text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-lg font-bold mt-1" style="color: #223362">Comercialización</h3>
        </a>
    </div>
</div>

<!-- Mobile View -->
<div class="lg:hidden p-4 space-y-4">
    <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 mb-3">
        <h2 class="text-xl font-bold text-azul-marino mb-1">
            Bienvenido {{ Auth::user()->name }} a <span class="tracking-[0.2em]">RUPAL</span></h2>
       
    </div>

    <!-- Modulos Mobile -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Perfil -->
        <a href="{{ route('profile') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-[0_0_10px_#F5B41033] hover:shadow-[0_0_16px_#F5B41055] transition-all flex flex-col items-center text-center">
            <div class="relative w-16 h-16 mb-2">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($profileCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($profileCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-slate-700">{{ $profileCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($profileCompleteness ?? 0) == 100)
                <span class="text-[10px] text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-sm font-bold mt-1" style="color: #223362">Perfil</h3>
        </a>

        <!-- Propiedades -->
        <a href="{{ route('propiedades.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-[0_0_10px_#F5B41033] hover:shadow-[0_0_16px_#F5B41055] transition-all flex flex-col items-center text-center">
            <div class="relative w-16 h-16 mb-2">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($propiedadesCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($propiedadesCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-slate-700">{{ $propiedadesCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($propiedadesCompleteness ?? 0) == 100)
                <span class="text-[10px] text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-sm font-bold mt-1" style="color: #223362">Propiedades</h3>
        </a>

        <!-- Cultivos -->
        <a href="{{ route('cultivos.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-[0_0_10px_#F5B41033] hover:shadow-[0_0_16px_#F5B41055] transition-all flex flex-col items-center text-center">
            <div class="relative w-16 h-16 mb-2">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($cultivosCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($cultivosCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-slate-700">{{ $cultivosCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($cultivosCompleteness ?? 0) == 100)
                <span class="text-[10px] text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-sm font-bold mt-1" style="color: #223362">Cultivos</h3>
        </a>

        <!-- Maquinarias -->
        <a href="{{ route('maquinaria.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-[0_0_10px_#F5B41033] hover:shadow-[0_0_16px_#F5B41055] transition-all flex flex-col items-center text-center">
            <div class="relative w-16 h-16 mb-2">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($maquinariasCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($maquinariasCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-slate-700">{{ $maquinariasCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($maquinariasCompleteness ?? 0) == 100)
                <span class="text-[10px] text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-sm font-bold mt-1" style="color: #223362">Maquinarias</h3>
        </a>

        <!-- Comercializacion -->
        <a href="{{ route('comercios.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-[0_0_10px_#F5B41033] hover:shadow-[0_0_16px_#F5B41055] transition-all col-span-2 flex flex-col items-center text-center">
            <div class="relative w-16 h-16 mb-2">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="{{ ($comercializacionCompleteness ?? 0) == 100 ? '#22c55e' : '#F5B410' }}" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 251.2 * (($comercializacionCompleteness ?? 0) / 100) }} 251.2"
                        class="transition-all duration-500"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-slate-700">{{ $comercializacionCompleteness ?? 0 }}%</span>
                </div>
            </div>
            @if(($comercializacionCompleteness ?? 0) == 100)
                <span class="text-[10px] text-green-500 font-semibold">Completado</span>
            @endif
            <h3 class="text-sm font-bold mt-1" style="color: #223362">Comercialización</h3>
        </a>
    </div>
</div>
@endsection