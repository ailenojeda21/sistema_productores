<?php

namespace App\Policies;

use App\Models\Propiedad;
use App\Models\User;

class PropiedadPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Propiedad $propiedad): bool
    {
        return $user->id === $propiedad->usuario_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Propiedad $propiedad): bool
    {
        return $user->id === $propiedad->usuario_id;
    }

    public function delete(User $user, Propiedad $propiedad): bool
    {
        return $user->id === $propiedad->usuario_id;
    }
}
