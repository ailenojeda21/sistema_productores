@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card max-w-xl mx-auto overflow-hidden">
        <div class="flex items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <i class="material-icons mr-3" style="font-size:24px;">edit_location_alt</i>
            <h2 class="text-xl font-bold">Editar Propiedad</h2>
        </div>

        @if (session('success'))
            <div class="sap-alert sap-alert-success mx-6 mt-6">
                <div class="flex items-center">
                    <i class="material-icons mr-3" style="font-size:24px;">check_circle</i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="p-6">
            <form method="POST" action="{{ route('propiedades.update', $propiedad) }}">
                @csrf
                @method('PUT')
                <div class="sap-form-group">
                    <label class="sap-form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="sap-form-input @error('nombre') error @enderror" value="{{ old('nombre', $propiedad->nombre) }}" required>
                    @error('nombre')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="ubicacion">Ubicación</label>
                    <input id="ubicacion" name="ubicacion" type="text" class="sap-form-input @error('ubicacion') error @enderror" value="{{ old('ubicacion', $propiedad->ubicacion) }}" required>
                    @error('ubicacion')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('propiedades.index') }}" class="sap-btn sap-btn-outline flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">arrow_back</i> Volver
                    </a>
                    <button type="submit" class="sap-btn sap-btn-primary flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">save</i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
