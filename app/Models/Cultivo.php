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

    public const VARIEDADES_HORTICOLA = [
        'Melón Blanco' => 'Melón Blanco',
        'Melón Amarillo' => 'Melón Amarillo',
        'Tomate Redondo' => 'Tomate Redondo',
        'Tomate Perita' => 'Tomate Perita',
        'Tomate Industria' => 'Tomate Industria',
        'Ajo Chino Morado' => 'Ajo Chino Morado',
        'Ajo Chino Blanco' => 'Ajo Chino Blanco',
        'Ajo Colorado' => 'Ajo Colorado',
        'Ajo Blanco' => 'Ajo Blanco',
        'Cebolla Sintética' => 'Cebolla Sintética',
        'Cebolla Torrentina' => 'Cebolla Torrentina',
        'Cebolla de Verdeo' => 'Cebolla de Verdeo',
        'Zapallo Anco' => 'Zapallo Anco',
        'Zapallito Redondo' => 'Zapallito Redondo',
        'Zapallito Zucchini' => 'Zapallito Zucchini',
        'Alcaucil' => 'Alcaucil',
        'Repollo' => 'Repollo',
        'Coliflor' => 'Coliflor',
        'Brócoli' => 'Brócoli',
        'Repollito de Bruselas' => 'Repollito de Bruselas',
        'Choclo Amarillo' => 'Choclo Amarillo',
        'Choclo Blanco' => 'Choclo Blanco',
        'Pimiento Cuatro Cascos' => 'Pimiento Cuatro Cascos',
        'Pimiento Calahorra' => 'Pimiento Calahorra',
        'Espinaca' => 'Espinaca',
        'Lechuga' => 'Lechuga',
        'Rabanito' => 'Rabanito',
        'Remolacha' => 'Remolacha',
        'Acelga' => 'Acelga',
        'Puerro' => 'Puerro',
        'Achicoria' => 'Achicoria',
        'Rúcula' => 'Rúcula',
        'Frutilla' => 'Frutilla',
    ];

    public const VARIEDADES_VITICOLA = [
        'Syrah' => 'Syrah',
        'Malbec' => 'Malbec',
        'Ancellotta' => 'Ancellotta',
        'Tempranillo' => 'Tempranillo',
        'Merlot' => 'Merlot',
        'Cereza' => 'Cereza',
        'Bonarda' => 'Bonarda',
        'Pedro Giménez' => 'Pedro Giménez',
        'Criolla' => 'Criolla',
        'Torrontés Riojano' => 'Torrontés Riojano',
        'Tintorera' => 'Tintorera',
        'Red Globe' => 'Red Globe',
        'Superior' => 'Superior',
        'Cardenal' => 'Cardenal',
        'Fernandina' => 'Fernandina',
        'Revelación' => 'Revelación',
        'Sorpresa' => 'Sorpresa',
        'Moscatel de Alejandría' => 'Moscatel de Alejandría',
        'Esperanza' => 'Esperanza',
        'Resistencia' => 'Resistencia',
        'Grandeza' => 'Grandeza',
        'Otras' => 'Otras',
    ];

    public const VARIEDADES_OLIVICOLA = [
        'Arbequina' => 'Arbequina',
        'Criolla' => 'Criolla',
        'Arauco' => 'Arauco',
        'Manzanilla' => 'Manzanilla',
        'Frantoio' => 'Frantoio',
        'Farga' => 'Farga',
        'Empeltre' => 'Empeltre',
        'Coratina' => 'Coratina',
    ];

    public const VARIEDADES_FRUTICOLA = [
        'Duraznero' => 'Duraznero',
        'Ciruelo' => 'Ciruelo',
        'Damazco' => 'Damazco',
        'Almendro' => 'Almendro',
        'Pistacho' => 'Pistacho',
        'Membrillo' => 'Membrillo',
    ];

    public static function getVariedadesForTipo(string $tipo): array
    {
        return match ($tipo) {
            'Hortícola' => self::VARIEDADES_HORTICOLA,
            'Vitícola' => self::VARIEDADES_VITICOLA,
            'Olivícola' => self::VARIEDADES_OLIVICOLA,
            'Frutícola' => self::VARIEDADES_FRUTICOLA,
            default => [],
        };
    }

    public static function getAllVariedades(): array
    {
        return array_merge(
            self::VARIEDADES_HORTICOLA,
            self::VARIEDADES_VITICOLA,
            self::VARIEDADES_OLIVICOLA,
            self::VARIEDADES_FRUTICOLA
        );
    }

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
