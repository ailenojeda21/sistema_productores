<?php

namespace App\Http\Requests;

use App\Models\Propiedad;
use Illuminate\Foundation\Http\FormRequest;

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
            : 999999;

        return [
            'propiedad_id' => 'required|exists:propiedades,id',
            'variedad' => 'required|string|max:255',
            'estacion' => 'required|string|max:255',
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
