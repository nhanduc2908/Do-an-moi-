<?php

namespace App\Models\Module05_UrlSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainBlacklist extends Model
{
    use HasFactory;

    protected $table = 'domain_blacklists';

    protected $fillable = [
        'domain', 'reason', 'added_by', 'added_at', 'expires_at'
    ];

    protected $casts = [
        'added_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}