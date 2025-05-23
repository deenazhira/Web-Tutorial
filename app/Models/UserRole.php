<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'role_name',
        'description',
    ];

    /**
     * Optional: Role has many permissions
     */
    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    /**
     * Role belongs to one user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
