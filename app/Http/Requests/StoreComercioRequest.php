<?php

namespace App\Http\Requests;

use App\Models\Comercio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComercioRequest extends FormRequest
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
            'mercados.*' => ['string', Rule::in(array_merge(array_keys(Comercio::MERCADOS), array_values(Comercio::MERCADOS)))],
            'tiene_cooperativas' => 'nullable',
            'cooperativas' => 'nullable|array',
            'cooperativas.*' => ['string', Rule::in(array_merge(array_keys(Comercio::COOPERATIVAS), array_values(Comercio::COOPERATIVAS)))],
        ];
    }
}
