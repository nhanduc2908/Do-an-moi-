<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name', 'display_name', 'description', 'level', 'is_system_role', 'guard_name'
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}