<?php

namespace App\Models\Module14_IncidentResponse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'incident_code', 'title', 'description', 'severity', 'status',
        'category', 'detected_at', 'reported_at', 'responded_at',
        'resolved_at', 'reported_by', 'assigned_to', 'resolution_summary'
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'reported_at' => 'datetime',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(IncidentComment::class);
    }

    public function evidences()
    {
        return $this->hasMany(ForensicEvidence::class);
    }

    public function rootCause()
    {
        return $this->hasOne(RootCause::class);
    }
}