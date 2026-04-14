<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comercio extends Model
{
    protected $fillable = [
        'usuario_id',
        'infraestructura_empaque',
        'vende_en_finca',
        'mercados',
        'cooperativas',
    ];

    protected $casts = [
        'mercados' => 'array',
        'cooperativas' => 'array',
    ];

    public const MERCADOS = [
        'mercado_guaymallen' => 'Mercado Cooperativo Guaymallen',
        'mercado_acceso_este' => 'Mercado Cooperativo Acceso Este',
        'mercado_las_heras' => 'Mercado Cooperativo Las Heras',
        'mercado_godoy_cruz' => 'Mercado Concentrador de Godoy Cruz',
        'mercado_colonia_bombal' => 'Mercado Cooperativo Colonia Bombal',
        'mercados_nacionales_internacionales' => 'Mercados Nacionales o Internacionales',
    ];

    public const COOPERATIVAS = [
        'cooperativa_nueva_california' => 'Coop. Nueva California',
        'cooperativa_tulumaya' => 'Coop. Tulumaya',
        'cooperativa_norte_mendocino' => 'Coop. Norte Mendocino',
        'cooperativa_tres_de_mayo' => 'Coop. Tres de Mayo',
        'cooperativa_altas_cumbres' => 'Coop. Altas Cumbres',
        'cooperativa_tres_portenas' => 'Coop. Tres Porteñas',
        'cooperativa_el_poniente' => 'Coop. El Poniente',
        'cooperativa_pampanos_mendocinos' => 'Coop. Pámpanos Mendocinos',
        'cooperativa_ingeniero_giagnoni' => 'Coop. Ingeniero Giagnoni',
        'cooperativa_las_trincheras' => 'Coop. Las Trincheras',
        'cooperativa_agricola_beltran' => 'Coop. Agrícola Beltrán',
        'cooperativa_la_dormida' => 'Coop. La Dormida',
        'cooperativa_del_algarrobal' => 'Coop. Del Algarrobal',
        'cooperativa_el_libertador' => 'Coop. El Libertador',
        'cooperativa_brindis' => 'Coop. Brindis',
        'cooperativa_productores_junin' => 'Coop. Productores de Junín',
        'cooperativa_colonia_california' => 'Coop. Colonia California',
        'cooperativa_mendoza' => 'Coop. Mendoza',
        'cooperativa_norte_lavallino' => 'Coop. Norte Lavallino',
        'cooperativa_maipu' => 'Coop. Maipú',
        'cooperativa_lacofut' => 'Coop. LACOFUT',
    ];

    public static function getMercadosForForm(): array
    {
        return self::MERCADOS;
    }

    public static function getCooperativasForForm(): array
    {
        return self::COOPERATIVAS;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
