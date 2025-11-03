<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'propiedad_id',
        'tractor',
        'modelo_tractor',
        'arado',
        'rastra',
        'niveleta_comun',
        'niveleta_laser',
        'cincel_cultivadora',
        'desmalezadora',
        'pulverizadora_tractor',
        'mochila_pulverizadora',
        'cosechadora',
        'enfardadora',
        'retroexcavadora',
        'carro_carreton',
    ];

    /**
     * Relación: Una maquinaria pertenece a una propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }

    /**
     * Relación: Una maquinaria puede tener muchos implementos
     * (Si necesitas esta relación, descomenta y ajusta)
     */
    // public function implementos()
    // {
    //     return $this->hasMany(Implemento::class);
    // }
}