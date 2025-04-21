<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    /**
     * Relación: Un archivo pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación: Un archivo pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    //
}
