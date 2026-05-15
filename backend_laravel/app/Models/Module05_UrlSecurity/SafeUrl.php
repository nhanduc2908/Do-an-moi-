<?php

namespace App\Models\Module05_UrlSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeUrl extends Model
{
    use HasFactory;

    protected $table = 'safe_urls';

    protected $fillable = [
        'url', 'domain', 'verified_by', 'verified_at', 'expires_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}