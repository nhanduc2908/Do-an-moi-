<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    use HasFactory;

    protected $table = 'api_settings';

    protected $fillable = [
        'api_name', 'endpoint', 'rate_limit', 'timeout',
        'retry_attempts', 'api_key', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}