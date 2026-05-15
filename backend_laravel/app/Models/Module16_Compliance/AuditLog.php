<?php

namespace App\Models\Module16_Compliance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'audit_type', 'standard_id', 'auditor', 'audited_at',
        'findings', 'recommendations', 'status', 'report_path'
    ];

    protected $casts = [
        'audited_at' => 'datetime',
        'findings' => 'array',
        'recommendations' => 'array',
    ];

    public function standard()
    {
        return $this->belongsTo(ComplianceStandard::class, 'standard_id');
    }
}