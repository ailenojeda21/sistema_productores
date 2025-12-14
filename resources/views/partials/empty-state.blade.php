<div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="bg-gray-50 rounded-full p-6 mb-6">
        <span class="material-symbols-outlined text-gray-300" style="font-size: 64px;">{{ $icon ?? 'inbox' }}</span>
    </div>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $title ?? 'No hay registros' }}</h3>
    <p class="text-gray-500 text-center max-w-md mb-6">
        {{ $message ?? 'Aún no se han agregado elementos. Comienza creando uno nuevo.' }}
    </p>
    @if(isset($action) && isset($actionUrl))
    <a href="{{ $actionUrl }}" class="px-6 py-3 bg-naranja-oscuro text-white rounded-lg hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold shadow-md flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        {{ $action }}
    </a>
    @endif
</div>
