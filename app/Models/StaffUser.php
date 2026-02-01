<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class StaffUser extends Authenticatable
{
    protected $table = 'staff_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAuditor(): bool
    {
        return $this->role === 'auditor';
    }
}
