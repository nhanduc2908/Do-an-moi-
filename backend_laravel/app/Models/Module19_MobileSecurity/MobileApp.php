<?php

namespace App\Models\Module19_MobileSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileApp extends Model
{
    use HasFactory;

    protected $table = 'mobile_apps';

    protected $fillable = [
        'app_name', 'package_name', 'version', 'permissions',
        'is_allowed', 'risk_level', 'category', 'signature_hash'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_allowed' => 'boolean',
    ];
}