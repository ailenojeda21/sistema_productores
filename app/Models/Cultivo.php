<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    protected $fillable = [
        'propiedad_id',
        'estacion',
        'tipo',
        'hectareas',
        'manejo_cultivo',
    ];

    /**
     * Relación: Un cultivo pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
