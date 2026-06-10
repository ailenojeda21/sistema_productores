<?php

namespace App\Policies;

use App\Models\Comercio;
use App\Models\User;

class ComercioPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comercio $comercio): bool
    {
        return $user->id === $comercio->usuario_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comercio $comercio): bool
    {
        return $user->id === $comercio->usuario_id;
    }

    public function delete(User $user, Comercio $comercio): bool
    {
        return $user->id === $comercio->usuario_id;
    }
}
