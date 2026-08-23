<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropiedadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calle' => 'required|string|max:255',
            'numeracion' => 'required|integer|min:1|max:999999',
            'distrito' => 'required|string|max:100',
            'hectareas' => 'required|numeric|min:0|max:1000',
            'malla' => 'nullable',
            'derecho_riego' => 'nullable',
            'tipo_derecho_riego' => 'nullable|string|in:Subterráneo,Superficial,Ambos',
            'rut' => 'nullable',
            'rut_valor' => ['nullable', 'required_with:rut_archivo_file', 'string', 'max:15', 'regex:/^\d+$/'],
            'rut_archivo_file' => 'nullable|file|mimes:pdf|max:10240',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'hectareas_malla' => 'nullable|numeric|lte:hectareas',
            'cierre_perimetral' => 'nullable',
            'tipo_tenencia' => 'required|in:propietario,arrendatario,otros',
            'especificar_tenencia' => 'nullable|required_if:tipo_tenencia,otros|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'especificar_tenencia.required_if' => 'Debe especificar la condición cuando selecciona "Otro".',
            'rut_valor.required_with' => 'El número de RUT es obligatorio cuando se adjunta un archivo.',
            'rut_valor.regex' => 'El número de RUT solo puede contener dígitos.',
            'rut_valor.max' => 'El número de RUT no puede superar los 15 dígitos.',
            'hectareas.max' => 'Las hectáreas no pueden superar 1000 por propiedad.',
        ];
    }
}
