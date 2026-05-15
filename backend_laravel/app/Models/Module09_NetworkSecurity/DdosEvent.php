<?php

namespace App\Models\Module09_NetworkSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DdosEvent extends Model
{
    use HasFactory;

    protected $table = 'ddos_events';

    protected $fillable = [
        'target_ip', 'attack_type', 'packets_per_second', 'bandwidth_mbps',
        'duration_seconds', 'mitigation_status', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}