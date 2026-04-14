<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
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
        'multiple',
    ];

    public const IMPLEMENTOS_LABELS = [
        'arado' => 'Arado',
        'rastra' => 'Rastra',
        'niveleta_comun' => 'Niveleta común',
        'niveleta_laser' => 'Niveleta láser',
        'cincel_cultivadora' => 'Cincel/Cultivadora',
        'desmalezadora' => 'Desmalezadora',
        'pulverizadora_tractor' => 'Pulverizadora',
        'mochila_pulverizadora' => 'Mochila pulverizadora',
        'cosechadora' => 'Cosechadora',
        'enfardadora' => 'Enfardadora',
        'retroexcavadora' => 'Retroexcavadora',
        'carro_carreton' => 'Carro/Carretón',
        'multiple' => 'Multiple',
    ];

    public static function getImplementosForForm(): array
    {
        return self::IMPLEMENTOS_LABELS;
    }

    public function getImplementosActivosAttribute(): array
    {
        $activos = [];
        foreach (self::IMPLEMENTOS_LABELS as $key => $label) {
            if (! empty($this->$key)) {
                $activos[] = $label;
            }
        }

        return $activos;
    }

    public function getTipoLabelAttribute(): string
    {
        return $this->tractor ? 'Tractor' : 'Maquinaria';
    }

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }
}
