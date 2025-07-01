@extends('layouts.app')

@section('styles')
style

@endsection


@section('content')
<div class="container mx-auto py-8">
   
        
        <div class="flex mt-3">
        <div class="flex-1">
             <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Nueva Propiedad</h2>
            <form method="POST" action="{{ route('propiedades.store') }}">
            @csrf
            
            <div class="mb-4">
                <label>Direcci&oacute;n</label>
                <input id="direccion" placeholder="Direcci&oacute;n">
            </div>
            <div class="mb-4" >
                <label   >Ubicaci&oacute;n</label>
                <input type="text" name="ubicacion" id="ubicacion" placeholder="Ubicacion">
            </div>
              <div>
                <label for="">Es propietario?</label>
                <input type="checkbox" name="es_propietario" id="es_propietario">
            </div>
            <div class="mb-4">
                <label>Hect&aacute;reas</label>
                <input id="hectareas">
                <span>M2</span>
            </div>
            <div>
                <label>Tiene derecho de riego?</label>
                <input type="checkbox" name="derecho_riego" id="derecho_riego">
            </div>
          

           
            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded">Guardar</button>
        </form>
             </div>
        </div>
        <div class="flex-1">
            <div id="map"></div>
        </div>

        
</div>
@endsection
