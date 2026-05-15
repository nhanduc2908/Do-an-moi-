<?php

namespace App\Models\Module06_ApiSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $table = 'api_keys';

    protected $fillable = [
        'name', 'key', 'user_id', 'permissions', 'rate_limit',
        'last_used_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }

    public function logs()
    {
        return $this->hasMany(ApiLog::class);
    }
}