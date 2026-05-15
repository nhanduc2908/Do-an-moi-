<?php

namespace App\Models\Module06_ApiSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEndpoint extends Model
{
    use HasFactory;

    protected $table = 'api_endpoints';

    protected $fillable = [
        'path', 'method', 'description', 'required_auth', 'rate_limit',
        'allowed_roles', 'is_active', 'created_by'
    ];

    protected $casts = [
        'allowed_roles' => 'array',
        'required_auth' => 'boolean',
        'is_active' => 'boolean',
    ];
}