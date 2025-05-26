@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card max-w-xl mx-auto overflow-hidden">
        <div class="flex items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <i class="material-icons mr-3" style="font-size:24px;">add_box</i>
            <h2 class="text-xl font-bold">Nuevo Implemento</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('implementos.store') }}">
                @csrf
                <div class="sap-form-group">
                    <label class="sap-form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="sap-form-input" required>
                    @error('nombre')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="tipo">Tipo</label>
                    <input id="tipo" name="tipo" type="text" class="sap-form-input" required>
                    @error('tipo')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('implementos.index') }}" class="sap-btn sap-btn-outline flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">arrow_back</i> Volver
                    </a>
                    <button type="submit" class="sap-btn sap-btn-primary flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">save</i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
