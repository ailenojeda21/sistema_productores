<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
        'es_propietario',
        'dni',
        'avatar', // <<--- AGREGADO
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
            return asset('storage/avatars/' . $this->avatar);
        }

        return asset('storage/avatars/uno.png'); // avatar por defecto
    }
}
