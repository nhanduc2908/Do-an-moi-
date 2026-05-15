<?php

namespace App\Models\Module05_UrlSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingUrl extends Model
{
    use HasFactory;

    protected $table = 'phishing_urls';

    protected $fillable = [
        'url', 'domain', 'report_count', 'first_reported_at', 
        'last_seen_at', 'status', 'targeted_brand'
    ];

    protected $casts = [
        'first_reported_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}