<?php

namespace App\Models\Module19_MobileSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilePolicy extends Model
{
    use HasFactory;

    protected $table = 'mobile_policies';

    protected $fillable = [
        'policy_name', 'version', 'require_encryption', 'require_pin',
        'min_pin_length', 'max_failed_attempts', 'inactivity_timeout',
        'allow_camera', 'allow_screenshot', 'allow_usb_transfer',
        'is_active', 'effective_date'
    ];

    protected $casts = [
        'require_encryption' => 'boolean',
        'require_pin' => 'boolean',
        'allow_camera' => 'boolean',
        'allow_screenshot' => 'boolean',
        'allow_usb_transfer' => 'boolean',
        'is_active' => 'boolean',
        'effective_date' => 'datetime',
    ];
}