<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    protected $fillable = [
        'usuario_id',
        'infraestructura_empaque',
        'vende_en_finca',
        'mercados',
        'cooperativas',
    ];

    protected $casts = [
        'mercados' => 'array',
        'cooperativas' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
