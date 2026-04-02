<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

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
            if (! empty($this->$field)) {
                $filled++;
            }
        }

        if (! empty($this->cooperativas) && is_array($this->cooperativas) && count($this->cooperativas) > 0) {
            $filled++;
        }

        return round(($filled / 5) * 100);
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
