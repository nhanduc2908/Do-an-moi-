<?php

namespace App\Models\Module13_LoggingMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreatEvent extends Model
{
    use HasFactory;

    protected $table = 'threat_events';

    protected $fillable = [
        'threat_type', 'severity', 'source_ip', 'destination_ip',
        'attack_vector', 'signature_id', 'payload', 'detected_at',
        'mitigation_status', 'mitigated_at'
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'mitigated_at' => 'datetime',
        'payload' => 'array',
    ];
}