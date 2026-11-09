<?php

namespace App\Policies;

use App\Models\StaffUser;

class StaffUserPolicy
{
    public function viewAny(StaffUser $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(StaffUser $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(StaffUser $user, StaffUser $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    public function update(StaffUser $user, StaffUser $model, ?string $newRole = null): bool
    {
        // Un usuario no puede cambiar su propio rol, aunque pueda editar
        // sus propios datos no sensibles (nombre, email, password, active).
        if ($user->id === $model->id) {
            $roleToCheck = $newRole ?? request()->input('role');
            if (is_string($roleToCheck) && $roleToCheck !== $model->role) {
                return false;
            }

            return true;
        }

        return $user->role === 'admin';
    }

    public function delete(StaffUser $user, StaffUser $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->role === 'admin';
    }
}
