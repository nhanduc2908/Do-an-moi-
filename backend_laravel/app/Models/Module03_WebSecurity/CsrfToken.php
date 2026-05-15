<?php

namespace App\Models\Module03_WebSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsrfToken extends Model
{
    use HasFactory;

    protected $table = 'csrf_tokens';

    protected $fillable = [
        'token', 'user_id', 'ip_address', 'expires_at', 'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}