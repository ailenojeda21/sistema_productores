<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    /**
     * Relación: Una maquinaria pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    /**
     * Relación: Una maquinaria tiene muchos implementos
     */
    public function implementos()
    {
        return $this->hasMany(Implemento::class, 'maquinaria_id');
    }

    //
}
