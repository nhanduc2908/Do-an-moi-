<?php

namespace App\Models\Module24_DevSecOps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretScanResult extends Model
{
    use HasFactory;

    protected $table = 'secret_scan_results';

    protected $fillable = [
        'repository', 'file_path', 'line_number', 'secret_type',
        'secret_hash', 'is_valid', 'is_revoked', 'detected_at'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_revoked' => 'boolean',
        'detected_at' => 'datetime',
    ];
}