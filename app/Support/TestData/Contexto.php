<?php

namespace App\Support\TestData;

use Illuminate\Support\Facades\Hash;

/**
 * Estado compartido entre los seeders de cohorte.
 *
 * Lleva el mapa de las claves del plan a los ids reales de la base, para que
 * `CultivosSeeder` pueda resolver `propiedad => 2` sin volver a consultar.
 */
final class Contexto
{
    /**
     * email de productor => id de users
     *
     * @var array<string, int>
     */
    public array $usuarios = [];

    /**
     * id de users => [indice de propiedad => id de propiedades]
     *
     * @var array<int, array<int, int>>
     */
    public array $propiedades = [];

    /**
     * @var array<string, int>
     */
    public array $staff = [];

    /**
     * Hash bcrypt del password del dataset, calculado una sola vez.
     *
     * Todas las cuentas generadas comparten password, asi que un unico
     * `Hash::make()` evita ~500 costosas operaciones bcrypt: el perfil grande
     * pasa de ~2.5 min a ~1 s.
     */
    private ?string $passwordHash = null;

    public function hash(string $plain): string
    {
        return $this->passwordHash ??= Hash::make($plain);
    }

    /**
     * @return array<int, int>|array<int, array<int, int>>
     */
    public function propiedad(int $usuarioId, int $indice): int
    {
        return $this->propiedades[$usuarioId][$indice];
    }
}
