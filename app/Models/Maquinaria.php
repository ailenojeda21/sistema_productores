<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'propiedad_id',  // Requerido (FK)
        'tipo',          // Campo principal según tu tabla
        // Nota: NO incluyas id, created_at, updated_at
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