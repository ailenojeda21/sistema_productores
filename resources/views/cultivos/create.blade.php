@extends('layouts.app')



@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-bold text-purple-700 mb-6">Nuevo Cultivo</h2>
            <form cass=max-w-sm mx-auto method="POST" action="{{ route('cultivos.store') }}">
                @csrf
                <div class="field">
                    <label>Nombre</label>
                    <input type="text" id="nombre" placeholder="nombre">
                </div>
                <div class="field">
                    <label>Tipo</label>
                    <input type="text" name="tipo" id="tipo" placeholder="Tipo">
                </div>
                <div class="field">
                    <label for="">Estaci&oacute;n </label>
                    <input type="text" name="estacion" id="estacion">
                </div>
                <div class="field">
                    <label>Hect&aacute;reas Totales</label>
                    <input type="number" id="hectareas">

                </div>
                <div class="field">
                    <label>Tiene malla antigranizo?</label>
                    <input type="checkbox" name="malla_antigranizo" id="malla_antigranizo">
                </div>
                <div class="field">
                    <label>Hect&aacute;reas con malla antigranizo</label>
                    <input type="number" name="hectareas_malla" id="hectareas_malla">
                </div>
                <div class="field">
                    <label>T&ecaron;cnologia de riego</label>
                    <div class="flex flex-row gap-5">
                        <select name="lista" id="lista" multiple class="flex-1">

                        </select>
                        <div class="flex flex-col flex-auto gap-3">
                            <button  >>></button>
                            <button class="border-gray-300 p-4"> -> </button>
                            <button class="border-gray-300 p-4"> <- </button>
                                    <button class="border-gray-300 p-4">
                                        << 
                                    </button>
                        </div>
                        <select name="disponibles" id="disponibles" multiple class="flex-1">
                            @foreach ($lista_tecnologias as $item)
                                <option value="{{ $item }}">{{$item}}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
                <button type="submit"
                    class="w-full bt-3 py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded">Guardar</button>
            </form>
        </div>
    </div>
@endsection