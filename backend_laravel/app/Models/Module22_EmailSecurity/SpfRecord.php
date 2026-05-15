<?php

namespace App\Models\Module22_EmailSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfRecord extends Model
{
    use HasFactory;

    protected $table = 'spf_records';

    protected $fillable = [
        'domain', 'record', 'allowed_ips', 'policy',
        'is_valid', 'last_checked_at', 'expires_at'
    ];

    protected $casts = [
        'allowed_ips' => 'array',
        'is_valid' => 'boolean',
        'last_checked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}