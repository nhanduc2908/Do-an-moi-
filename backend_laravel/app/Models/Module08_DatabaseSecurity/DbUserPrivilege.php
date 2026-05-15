<?php

namespace App\Models\Module08_DatabaseSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbUserPrivilege extends Model
{
    use HasFactory;

    protected $table = 'db_user_privileges';

    protected $fillable = [
        'username', 'database_name', 'privileges', 'granted_by', 'granted_at'
    ];

    protected $casts = [
        'privileges' => 'array',
        'granted_at' => 'datetime',
    ];
}