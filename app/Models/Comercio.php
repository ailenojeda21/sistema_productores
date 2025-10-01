<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    protected $fillable = [
        'usuario_id',
        'infraestructura_empaque',
        'comercio_feria',
        'vende_en_finca',
        'nombre_feria',
        'ferias',
    ];

    protected $casts = [
        'ferias' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
