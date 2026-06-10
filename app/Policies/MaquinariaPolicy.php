<?php

namespace App\Policies;

use App\Models\Maquinaria;
use App\Models\User;

class MaquinariaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Maquinaria $maquinaria): bool
    {
        return $user->id === $maquinaria->propiedad->usuario_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Maquinaria $maquinaria): bool
    {
        return $user->id === $maquinaria->propiedad->usuario_id;
    }

    public function delete(User $user, Maquinaria $maquinaria): bool
    {
        return $user->id === $maquinaria->propiedad->usuario_id;
    }
}
