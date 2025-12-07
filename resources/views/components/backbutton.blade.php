<button type="button" onclick="window.location.href = '{{ $route ?? url()->previous() }}'"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
    <i class="fas fa-arrow-left mr-2"></i>
    Volver
</button>
