<?php

namespace App\Models\Module07_SourceCode;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretDetected extends Model
{
    use HasFactory;

    protected $table = 'secrets_detected';

    protected $fillable = [
        'code_scan_result_id', 'file_path', 'line_number', 'secret_type',
        'secret_value_hash', 'is_valid', 'is_revoked', 'revoked_at'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_revoked' => 'boolean',
        'revoked_at' => 'datetime',
    ];
}