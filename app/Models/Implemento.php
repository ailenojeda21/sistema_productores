<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Implemento extends Model
{
    /**
     * Relación: Un implemento pertenece a una maquinaria
     */
    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'maquinaria_id');
    }

    //
}
