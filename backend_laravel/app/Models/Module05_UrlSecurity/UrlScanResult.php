<?php

namespace App\Models\Module05_UrlSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlScanResult extends Model
{
    use HasFactory;

    protected $table = 'url_scan_results';

    protected $fillable = [
        'url', 'status', 'risk_level', 'redirects_to', 'is_malicious',
        'categories', 'scan_duration', 'screenshot_path'
    ];

    protected $casts = [
        'categories' => 'array',
        'is_malicious' => 'boolean',
    ];
}