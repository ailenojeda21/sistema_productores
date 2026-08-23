<?php

namespace App\Http\Requests;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCultivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $propiedad = Propiedad::where('id', $this->input('propiedad_id'))
            ->where('usuario_id', auth()->id())
            ->first();

        $hectareasDisponibles = $propiedad
            ? $propiedad->hectareas_disponibles
            : 0;

        return [
            'propiedad_id' => [
                'required',
                Rule::exists('propiedades', 'id')->where('usuario_id', auth()->id()),
            ],
            'variedad' => 'required|string|max:255',
            'estacion' => ['required', Rule::in(array_keys(Cultivo::ESTACIONES))],
            'tipo' => ['required', 'string', 'max:255', 'regex:/^[\pL\pM0-9\s\-\.\,\/]+$/u'],
            'hectareas' => "required|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'required|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'required|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.regex' => 'El tipo contiene caracteres no permitidos.',
        ];
    }
}
