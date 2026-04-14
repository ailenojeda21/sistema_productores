<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    protected $fillable = [
        'propiedad_id',
        'variedad',
        'estacion',
        'tipo',
        'hectareas',
        'manejo_cultivo',
        'tecnologia_riego',
    ];

    public const TIPOS = [
        'Frutícola' => 'Frutícola',
        'Hortícola' => 'Hortícola',
        'Vitícola' => 'Vitícola',
        'Olivícola' => 'Olivícola',
    ];

    public const MANEJO_OPTIONS = [
        'Convencional' => 'Convencional',
        'Agroecologico' => 'Agroecológico',
        'Organico' => 'Orgánico',
    ];

    public const ESTACIONES = [
        'Verano' => 'Verano',
        'Invierno' => 'Invierno',
        'Otoño' => 'Otoño',
        'Primavera' => 'Primavera',
    ];

    public const TECNOLOGIA_RIEGO = [
        'Surco' => 'Por Surco',
        'Inundación' => 'Por Inundación',
        'Cimalco' => 'Cimalco',
        'Manga' => 'Manga',
        'Goteo' => 'Goteo',
        'Aspersión' => 'Aspersión',
    ];

    public static function getTiposForForm(): array
    {
        return self::TIPOS;
    }

    public static function getManejoOptionsForForm(): array
    {
        return self::MANEJO_OPTIONS;
    }

    public static function getEstacionesForForm(): array
    {
        return self::ESTACIONES;
    }

    public static function getTecnologiaRiegoForForm(): array
    {
        return self::TECNOLOGIA_RIEGO;
    }

    public function getManejoLabelAttribute(): string
    {
        return self::MANEJO_OPTIONS[$this->manejo_cultivo] ?? $this->manejo_cultivo;
    }

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
