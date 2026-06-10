<?php

namespace App\Policies;

use App\Models\Cultivo;
use App\Models\User;

class CultivoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cultivo $cultivo): bool
    {
        return $user->id === $cultivo->propiedad->usuario_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Cultivo $cultivo): bool
    {
        return $user->id === $cultivo->propiedad->usuario_id;
    }

    public function delete(User $user, Cultivo $cultivo): bool
    {
        return $user->id === $cultivo->propiedad->usuario_id;
    }
}
