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

    public function update(StaffUser $user, StaffUser $model): bool
    {
        if ($user->id === $model->id) {
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
