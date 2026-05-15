<?php

namespace App\Models\Module20_IotSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    use HasFactory;

    protected $table = 'iot_devices';

    protected $fillable = [
        'device_id', 'device_type', 'manufacturer', 'model',
        'firmware_version', 'ip_address', 'mac_address',
        'location', 'status', 'last_seen_at', 'is_compromised'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_compromised' => 'boolean',
    ];

    public function alerts()
    {
        return $this->hasMany(IotAlert::class);
    }

    public function traffic()
    {
        return $this->hasMany(IotTraffic::class);
    }
}