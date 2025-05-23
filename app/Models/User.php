<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'avatar',
        'phone',
        'city',
        'two_factor_code',
        'two_factor_expires_at',
        'salt',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * One-to-one: A user has one role
     */
    public function role()
    {
        return $this->hasOne(UserRole::class, 'user_id', 'id');
    }

    /**
     * Check if the user has a given role
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->role_name === $roleName;
    }

    /**
     * Optional: Check if user has a permission
     */
    public function hasPermission($permission)
    {
        return $this->role && $this->role->permissions->contains('description', $permission);
    }
}
