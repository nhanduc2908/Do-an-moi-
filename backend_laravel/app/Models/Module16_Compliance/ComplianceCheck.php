<?php

namespace App\Models\Module16_Compliance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceCheck extends Model
{
    use HasFactory;

    protected $table = 'compliance_checks';

    protected $fillable = [
        'standard_id', 'control_id', 'check_name', 'status',
        'score', 'evidence_path', 'checked_by', 'checked_at',
        'due_date', 'remarks'
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function standard()
    {
        return $this->belongsTo(ComplianceStandard::class, 'standard_id');
    }

    public function control()
    {
        return $this->belongsTo(ComplianceControl::class, 'control_id');
    }

    public function checker()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'checked_by');
    }
}