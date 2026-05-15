<?php

namespace App\Models\Module10_EndpointSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsbDevice extends Model
{
    use HasFactory;

    protected $table = 'usb_devices';

    protected $fillable = [
        'device_id', 'vendor', 'product', 'serial_number', 'first_seen_at',
        'last_seen_at', 'is_allowed', 'blocked_reason'
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_allowed' => 'boolean',
    ];
}