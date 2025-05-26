@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 p-4">
    <div class="w-full max-w-lg p-0 bg-white rounded-lg shadow-lg overflow-hidden sap-card">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-center">
                <i class="material-icons mr-3" style="font-size:28px;">error_outline</i>
                <h2 class="text-2xl font-bold">Error 404</h2>
            </div>
        </div>

        <div class="p-8 flex flex-col items-center justify-center text-center">
            <div class="text-9xl font-bold text-blue-200 mb-4">404</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Página no encontrada</h3>
            <p class="text-gray-600 mb-8">Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>

            <div class="flex space-x-4">
                <a href="javascript:history.back()" class="sap-btn sap-btn-outline flex items-center">
                    <i class="material-icons mr-2" style="font-size:18px;">arrow_back</i>
                    Volver atrás
                </a>
                <a href="{{ route('dashboard') }}" class="sap-btn sap-btn-primary flex items-center">
                    <i class="material-icons mr-2" style="font-size:18px;">home</i>
                    Ir al inicio
                </a>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center text-gray-500 text-xs">
            <p>&copy; {{ date('Y') }} Sistema Agrícola SAP. Todos los derechos reservados.</p>
        </div>
    </div>
</div>
@endsection
