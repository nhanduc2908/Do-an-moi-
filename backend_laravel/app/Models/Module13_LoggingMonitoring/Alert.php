<?php

namespace App\Models\Module13_LoggingMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'alerts';

    protected $fillable = [
        'alert_name', 'severity', 'status', 'source', 'message',
        'triggered_at', 'acknowledged_at', 'resolved_at', 'acknowledged_by',
        'correlation_rule_id', 'details'
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'details' => 'array',
    ];

    public function acknowledger()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'acknowledged_by');
    }

    public function rule()
    {
        return $this->belongsTo(CorrelationRule::class, 'correlation_rule_id');
    }
}