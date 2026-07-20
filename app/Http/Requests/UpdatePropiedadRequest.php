<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calle' => 'sometimes|required|string|max:255',
            'numeracion' => 'sometimes|required|integer|min:1',
            'distrito' => 'sometimes|required|string|max:100',
            'hectareas' => 'sometimes|required|numeric|min:0',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',
            'rut' => 'nullable',
            'rut_valor' => 'nullable|required_with:rut_archivo_file',
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',
            'lat' => 'sometimes|required|numeric|between:-90,90',
            'lng' => 'sometimes|required|numeric|between:-180,180',
            'hectareas_malla' => 'nullable|numeric|lte:hectareas',
            'cierre_perimetral' => 'nullable',
            'tipo_tenencia' => 'sometimes|required|in:propietario,arrendatario,otros',
            'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
            'rut_valor.required_with' => 'El número de RUT es obligatorio cuando se adjunta un archivo.',
        ];
    }
}
