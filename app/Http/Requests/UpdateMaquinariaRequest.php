<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaquinariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'propiedad_id' => 'required|exists:propiedades,id',
            'modelo_tractor' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.date('Y'),
                function ($attr, $value, $fail) {
                    if ($this->has('tractor') && empty($value)) {
                        $fail('El año del tractor es obligatorio cuando se marca la opción tractor.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'modelo_tractor.integer' => 'El año del tractor debe ser un número entero.',
        ];
    }
}
