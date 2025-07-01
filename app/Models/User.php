<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
    /**
     * Relación: Un usuario tiene muchos archivos
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'usuario_id');
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
