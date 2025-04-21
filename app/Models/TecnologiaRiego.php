<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TecnologiaRiego extends Model
{
    /**
     * Relación: Una tecnología de riego pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    //
}
