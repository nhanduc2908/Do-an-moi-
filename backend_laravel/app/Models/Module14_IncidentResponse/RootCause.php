<?php

namespace App\Models\Module14_IncidentResponse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RootCause extends Model
{
    use HasFactory;

    protected $table = 'root_causes';

    protected $fillable = [
        'incident_id', 'cause_category', 'description', 'contributing_factors',
        'underlying_issues', 'recommendations', 'analyzed_by', 'analyzed_at'
    ];

    protected $casts = [
        'contributing_factors' => 'array',
        'underlying_issues' => 'array',
        'recommendations' => 'array',
        'analyzed_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function analyst()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'analyzed_by');
    }
}