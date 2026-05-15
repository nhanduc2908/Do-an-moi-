<?php

namespace App\Models\Module17_PhysicalSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartLock extends Model
{
    use HasFactory;

    protected $table = 'smart_locks';

    protected $fillable = [
        'lock_id', 'door_name', 'location', 'lock_type',
        'battery_level', 'firmware_version', 'is_online',
        'last_activity_at', 'is_locked'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'is_locked' => 'boolean',
        'last_activity_at' => 'datetime',
    ];
}