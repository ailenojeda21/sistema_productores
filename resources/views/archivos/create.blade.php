@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="sap-card max-w-xl mx-auto overflow-hidden">
        <div class="flex items-center p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <i class="material-icons mr-3" style="font-size:24px;">upload_file</i>
            <h2 class="text-xl font-bold">Nuevo Archivo</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('archivos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="sap-form-group">
                    <label class="sap-form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="sap-form-input" required>
                    @error('nombre')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sap-form-group">
                    <label class="sap-form-label" for="archivo">Archivo</label>
                    <div class="flex items-center p-2 border border-gray-300 rounded-md bg-gray-50">
                        <input id="archivo" name="archivo" type="file" class="flex-1 text-sm text-gray-700" required>
                    </div>
                    @error('archivo')
                        <p class="sap-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('archivos.index') }}" class="sap-btn sap-btn-outline flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">arrow_back</i> Volver
                    </a>
                    <button type="submit" class="sap-btn sap-btn-primary flex items-center">
                        <i class="material-icons mr-2" style="font-size:18px;">cloud_upload</i> Subir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
