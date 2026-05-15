<?php

namespace App\Models\Module20_IotSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotTraffic extends Model
{
    use HasFactory;

    protected $table = 'iot_traffic';

    protected $fillable = [
        'device_id', 'destination_ip', 'destination_port',
        'protocol', 'bytes_sent', 'bytes_received', 'timestamp',
        'is_suspicious', 'threat_type'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'is_suspicious' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(IotDevice::class);
    }
}