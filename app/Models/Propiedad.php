<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table = "propiedades";

    /**
     * Relación: Una propiedad pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación: Una propiedad tiene muchos archivos
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'propiedad_id');
    }

    /**
     * Relación: Una propiedad tiene muchas maquinarias
     */
    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class, 'propiedad_id');
    }

    /**
     * Relación: Una propiedad tiene muchos cultivos
     */
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class, 'propiedad_id');
    }

    /**
     * Relación: Una propiedad tiene muchas tecnologías de riego
     */
    // public function tecnologiaRiegos()
    // {
    //     return $this->hasMany(TecnologiaRiego::class, 'propiedad_id');
    // }

    //
}
