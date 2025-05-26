@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100">
    <div class="bg-white rounded-md shadow-lg p-8 max-w-4xl w-full border-t-4 border-blue-600">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-800">Panel de Control SAP</h1>
            <div class="flex items-center">
                <span class="text-gray-600 mr-4">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold shadow hover:bg-blue-700 flex items-center">
                        <i class="material-icons align-middle mr-1" style="font-size:18px;">logout</i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-600 text-blue-800">
            <p>Bienvenido al sistema de gestión agrícola. Seleccione un módulo para comenzar a trabajar.</p>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="{{ route('propiedades.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">home_work</i>
                <span class="text-gray-700 font-medium">Propiedades</span>
            </a>
            <a href="{{ route('archivos.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">folder</i>
                <span class="text-gray-700 font-medium">Archivos</span>
            </a>
            <a href="{{ route('maquinaria.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">agriculture</i>
                <span class="text-gray-700 font-medium">Maquinaria</span>
            </a>
            <a href="{{ route('implementos.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">build</i>
                <span class="text-gray-700 font-medium">Implementos</span>
            </a>
            <a href="{{ route('cultivos.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">eco</i>
                <span class="text-gray-700 font-medium">Cultivos</span>
            </a>
            <a href="{{ route('tecnologia_riego.index') }}" class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md hover:border-blue-400 transition-all">
                <i class="material-icons text-blue-600 mb-2" style="font-size:32px;">water_drop</i>
                <span class="text-gray-700 font-medium">Tecnología Riego</span>
            </a>
        </div>

        <div class="mt-6 p-4 border border-gray-200 rounded-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Acceso Rápido</h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center">
                    <i class="material-icons mr-1" style="font-size:16px;">person</i> Perfil
                </a>
                <a href="#" class="px-4 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center" onclick="event.preventDefault(); openMdiWindow('nueva-propiedad')">
                    <i class="material-icons mr-1" style="font-size:16px;">add</i> Nueva Propiedad
                </a>
                <a href="#" class="px-4 py-2 text-sm text-blue-600 border border-blue-200 rounded hover:bg-blue-50 flex items-center" onclick="event.preventDefault(); openMdiWindow('nuevo-cultivo')">
                    <i class="material-icons mr-1" style="font-size:16px;">add</i> Nuevo Cultivo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MDI Windows container (hidden by default) -->
<div id="mdi-container" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div id="nueva-propiedad" class="mdi-window bg-white rounded-md shadow-lg p-6 max-w-lg w-full hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-blue-800">Nueva Propiedad</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeMdiWindow('nueva-propiedad')">
                <i class="material-icons">close</i>
            </button>
        </div>
        <form method="POST" action="{{ route('propiedades.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="ubicacion">Ubicación</label>
                <input id="ubicacion" name="ubicacion" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Guardar</button>
        </form>
    </div>

    <div id="nuevo-cultivo" class="mdi-window bg-white rounded-md shadow-lg p-6 max-w-lg w-full hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-blue-800">Nuevo Cultivo</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeMdiWindow('nuevo-cultivo')">
                <i class="material-icons">close</i>
            </button>
        </div>
        <form method="POST" action="{{ route('cultivos.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="tipo">Tipo</label>
                <input id="tipo" name="tipo" type="text" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Guardar</button>
        </form>
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
        const visibleWindows = document.querySelectorAll('.mdi-window:not(.hidden)');
        if (visibleWindows.length === 0) {
            document.getElementById('mdi-container').classList.add('hidden');
        }
    }
</script>
@endsection
