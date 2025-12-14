<div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="bg-gray-50 rounded-full p-6 mb-6">
        <span class="material-symbols-outlined text-gray-300" style="font-size: 64px;">precision_manufacturing</span>
    </div>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay maquinaria registrada</h3>
    <p class="text-gray-500 text-center max-w-md mb-6">
        Registra tu primera maquinaria y sus implementos para llevar un mejor control de tus recursos.
    </p>
    <a href="{{ route('maquinaria.create') }}" class="px-6 py-3 bg-naranja-oscuro text-white rounded-lg hover:bg-amarillo-claro hover:text-azul-marino transition font-semibold shadow-md flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        Nueva Maquinaria
    </a>
</div>
