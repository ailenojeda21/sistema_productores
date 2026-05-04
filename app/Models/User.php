<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    public const COOPERATIVAS = [
        'cooperativa_nueva_california' => 'Coop Nueva California',
        'cooperativa_tulumaya' => 'Coop Tulumaya',
        'cooperativa_norte_mendocino' => 'Coop Norte Mendocino',
        'cooperativa_tres_de_mayo' => 'Coop Tres de Mayo',
        'cooperativa_altas_cumbres' => 'Coop Altas Cumbres',
        'cooperativa_tres_portenas' => 'Coop Tres Porteñas',
        'cooperativa_el_poniente' => 'Coop El Poniente',
        'cooperativa_pampanos_mendocinos' => 'Coop Pámpanos Mendocinos',
        'cooperativa_ingeniero_giagnoni' => 'Coop Ingeniero Giagnoni',
        'cooperativa_las_trincheras' => 'Coop Las Trincheras',
        'cooperativa_agricola_beltran' => 'Coop Agrícola Beltrán',
        'cooperativa_la_dormida' => 'Coop La Dormida',
        'cooperativa_del_algarrobal' => 'Coop Del Algarrobal',
        'cooperativa_el_libertador' => 'Coop El Libertador',
        'cooperativa_brindis' => 'Coop Brindis',
        'cooperativa_productores_junin' => 'Coop Productores de Junín',
        'cooperativa_colonia_california' => 'Coop Colonia California',
        'cooperativa_mendoza' => 'Coop Mendoza',
        'cooperativa_norte_lavallino' => 'Coop Norte Lavallino',
        'cooperativa_maipu' => 'Coop Maipú',
        'cooperativa_lacofrut' => 'Coop Lacofrut',
    ];

    public static function getCooperativasForForm(): array
    {
        return self::COOPERATIVAS;
    }

    /**
     * Relación: Un usuario tiene muchas propiedades
     */
    public function propiedades()
    {
        return $this->hasMany(Propiedad::class, 'usuario_id');
    }

    public function comercializacion()
    {
        return $this->hasMany(Comercio::class, 'usuario_id');
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'usuario_id');
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'telefono',
        'avatar',
        'cooperativas',
    ];

    /**
     * The attributes that should be hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cooperativas' => 'array',
        ];
    }

    /**
     * Accesor: URL del avatar.
     * Si el usuario tiene avatar, devuelve la imagen elegida.
     * Si no, devuelve un avatar por defecto.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/'.$this->avatar);
        }

        return asset('storage/avatars/uno.png');
    }

  public function getProfileCompletenessAttribute(): int
{
    $fields = ['name', 'email', 'dni', 'telefono'];
    $filled = 0;

    foreach ($fields as $field) {
        if (!empty($this->$field)) {
            $filled++;
        }
    }

    return round(($filled / 4) * 100);
}

    public function getPropiedadesCompletenessAttribute(): int
    {
        return $this->propiedades()->exists() ? 100 : 0;
    }

    public function getCultivosCompletenessAttribute(): int
    {
        $hasCultivos = \App\Models\Cultivo::whereHas('propiedad', function ($query) {
            $query->where('usuario_id', $this->id);
        })->exists();

        return $hasCultivos ? 100 : 0;
    }

    public function getMaquinariasCompletenessAttribute(): int
    {
        $hasMaquinarias = \App\Models\Maquinaria::whereHas('propiedad', function ($query) {
            $query->where('usuario_id', $this->id);
        })->exists();

        return $hasMaquinarias ? 100 : 0;
    }

    public function getComercializacionCompletenessAttribute(): int
    {
        return $this->comercializacion()->exists() ? 100 : 0;
    }
}
