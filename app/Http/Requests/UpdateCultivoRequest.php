<?php

namespace App\Http\Requests;

use App\Models\Cultivo;
use App\Models\Propiedad;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCultivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cultivoId = $this->route('cultivo');
        $cultivo = $cultivoId ? Cultivo::find($cultivoId) : null;
        $propiedadId = $this->input('propiedad_id') ?? ($cultivo?->propiedad_id);

        $propiedad = Propiedad::where('id', $propiedadId)
            ->where('usuario_id', auth()->id())
            ->first();

        $hectareasDisponibles = 999999;
        if ($propiedad) {
            $hectareasUsadas = $propiedad->cultivos()
                ->when($cultivoId, fn ($q) => $q->where('id', '!=', $cultivoId))
                ->sum('hectareas');
            $hectareasDisponibles = max(0, $propiedad->hectareas - $hectareasUsadas);
        }

        return [
            'propiedad_id' => 'sometimes|exists:propiedades,id',
            'variedad' => 'sometimes|string|max:255',
            'estacion' => 'sometimes|string|max:255',
            'tipo' => ['sometimes', 'string', 'max:255', 'regex:/^[\pL\pM0-9\s\-\.\,\/]+$/u'],
            'hectareas' => "sometimes|numeric|min:0|max:$hectareasDisponibles",
            'manejo_cultivo' => 'sometimes|in:Convencional,Agroecologico,Organico',
            'tecnologia_riego' => 'sometimes|in:Surco,Inundación,Cimalco,Manga,Goteo,Aspersión',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.regex' => 'El tipo contiene caracteres no permitidos.',
        ];
    }
}
