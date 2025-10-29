<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Importar el modelo User

class Propiedad extends Model
{
    protected $table = "propiedades";

    // Permitir asignación masiva de todos los campos de la tabla
    protected $fillable = [
        'usuario_id',
        'direccion',
        'hectareas',
        'es_propietario',
        'derecho_riego',
        'tipo_derecho_riego',
        'rut',
        'rut_valor',
        'rut_archivo',
        'lat',
        'lng',
        'hectareas_malla',
        'cierre_perimetral',
        'malla',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

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
}
