@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="sap-card max-w-5xl mx-auto shadow-lg overflow-hidden">            <div class="flex justify-between items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <h1 class="text-2xl font-bold">Panel de Control</h1>
            <div class="flex items-center flex-col-mobile space-y-3-mobile">
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm mr-3">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-blue-600 rounded text-sm font-semibold shadow hover:bg-blue-50 flex items-center">
                        <i class="material-icons align-middle mr-1" style="font-size:18px;">logout</i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border-l-4 border-blue-500">
                <i class="material-icons text-blue-500 mr-3" style="font-size:24px;">info</i>
                <p class="text-blue-800">Bienvenido al sistema de gestión agrícola. Seleccione un módulo para comenzar a trabajar.</p>
            </div>
        </div>

        <div class="p-6 bg-white">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Módulos del Sistema</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('propiedades.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">home_work</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Propiedades</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Gestión de propiedades y lotes</p>
                </a>

                <a href="{{ route('archivos.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">folder</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Archivos</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Administración de documentos</p>
                </a>

                <a href="{{ route('maquinaria.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">agriculture</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Maquinaria</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Control de maquinaria agrícola</p>
                </a>

                <a href="{{ route('implementos.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">build</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Implementos</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Gestión de implementos agrícolas</p>
                </a>

                <a href="{{ route('cultivos.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">eco</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Cultivos</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Administración de cultivos</p>
                </a>

                <a href="{{ route('tecnologia_riego.index') }}" class="sap-card flex flex-col items-center p-6 hover:shadow-md hover:border-blue-400 transition-all group">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-600 mb-4 transition-colors">
                        <i class="material-icons text-blue-600 group-hover:text-white transition-colors" style="font-size:32px;">water_drop</i>
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-blue-600 transition-colors">Tecnología Riego</span>
                    <p class="text-gray-500 text-sm text-center mt-2">Control de sistemas de riego</p>
                </a>
            </div>

            <div class="mt-8 p-5 border border-gray-200 rounded-lg bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Acceso Rápido</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white text-blue-600 border border-blue-200 rounded-md hover:bg-blue-50 flex items-center shadow-sm w-full-mobile sm:w-auto">
                        <i class="material-icons mr-2" style="font-size:18px;">person</i> Perfil
                    </a>
                    <a href="#" class="px-4 py-2 bg-white text-blue-600 border border-blue-200 rounded-md hover:bg-blue-50 flex items-center shadow-sm w-full-mobile sm:w-auto" onclick="event.preventDefault(); openMdiWindow('nueva-propiedad')">
                        <i class="material-icons mr-2" style="font-size:18px;">add</i> Nueva Propiedad
                    </a>
                    <a href="#" class="px-4 py-2 bg-white text-blue-600 border border-blue-200 rounded-md hover:bg-blue-50 flex items-center shadow-sm w-full-mobile sm:w-auto" onclick="event.preventDefault(); openMdiWindow('nuevo-cultivo')">
                        <i class="material-icons mr-2" style="font-size:18px;">add</i> Nuevo Cultivo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MDI Windows container (hidden by default) -->
<div id="mdi-container" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div id="nueva-propiedad" class="sap-mdi-window hidden max-w-lg w-full">
        <div class="sap-mdi-header">
            <h3 class="sap-mdi-title">Nueva Propiedad</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeMdiWindow('nueva-propiedad')">
                <i class="material-icons">close</i>
            </button>
        </div>
        <div class="sap-mdi-body">
            <form method="POST" action="{{ route('propiedades.store') }}">
                @csrf
                <div class="sap-form-group">
                    <label class="sap-form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="sap-form-input" required>
                </div>
                <div class="sap-form-group">
                    <label class="sap-form-label" for="ubicacion">Ubicación</label>
                    <input id="ubicacion" name="ubicacion" type="text" class="sap-form-input" required>
                </div>
                <div class="sap-mdi-footer">
                    <button type="button" class="sap-btn sap-btn-outline" onclick="closeMdiWindow('nueva-propiedad')">Cancelar</button>
                    <button type="submit" class="sap-btn sap-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="nuevo-cultivo" class="sap-mdi-window hidden max-w-lg w-full">
        <div class="sap-mdi-header">
            <h3 class="sap-mdi-title">Nuevo Cultivo</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeMdiWindow('nuevo-cultivo')">
                <i class="material-icons">close</i>
            </button>
        </div>
        <div class="sap-mdi-body">
            <form method="POST" action="{{ route('cultivos.store') }}">
                @csrf
                <div class="sap-form-group">
                    <label class="sap-form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="sap-form-input" required>
                </div>
                <div class="sap-form-group">
                    <label class="sap-form-label" for="tipo">Tipo</label>
                    <input id="tipo" name="tipo" type="text" class="sap-form-input" required>
                </div>
                <div class="sap-mdi-footer">
                    <button type="button" class="sap-btn sap-btn-outline" onclick="closeMdiWindow('nuevo-cultivo')">Cancelar</button>
                    <button type="submit" class="sap-btn sap-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openMdiWindow(windowId) {
        document.getElementById('mdi-container').classList.remove('hidden');
        document.getElementById(windowId).classList.remove('hidden');
    }

    function closeMdiWindow(windowId) {
        document.getElementById(windowId).classList.add('hidden');
        // Check if any window is still visible
        const visibleWindows = document.querySelectorAll('.sap-mdi-window:not(.hidden)');
        if (visibleWindows.length === 0) {
            document.getElementById('mdi-container').classList.add('hidden');
        }
    }
</script>
@endsection
