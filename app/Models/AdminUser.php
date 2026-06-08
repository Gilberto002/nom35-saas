<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }
}