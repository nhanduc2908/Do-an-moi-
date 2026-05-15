<?php

namespace App\Models\Module14_IncidentResponse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryLog extends Model
{
    use HasFactory;

    protected $table = 'recovery_logs';

    protected $fillable = [
        'incident_id', 'action', 'performed_by', 'performed_at',
        'result', 'details', 'duration_minutes'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'details' => 'array',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function performer()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'performed_by');
    }
}