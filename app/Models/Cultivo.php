<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    protected $fillable = [
        'propiedad_id',
        'nombre',
        'estacion',
        'tipo',
        'hectareas',
        'manejo_cultivo',
        'tecnologia_riego',
    ];

    /**
     * Relación: Un cultivo pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
