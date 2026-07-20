<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComercioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'infraestructura_empaque' => 'nullable',
            'vende_en_finca' => 'nullable',
            'tiene_mercados' => 'nullable',
            'mercados' => 'nullable|array',
            'tiene_cooperativas' => 'nullable',
            'cooperativas' => 'nullable|array',
        ];
    }
}
