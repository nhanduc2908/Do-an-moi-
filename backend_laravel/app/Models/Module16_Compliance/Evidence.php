<?php

namespace App\Models\Module16_Compliance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidences';

    protected $fillable = [
        'compliance_check_id', 'evidence_type', 'file_path',
        'hash_value', 'collected_by', 'collected_at', 'valid_until'
    ];

    protected $casts = [
        'collected_at' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function complianceCheck()
    {
        return $this->belongsTo(ComplianceCheck::class);
    }

    public function collector()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'collected_by');
    }
}