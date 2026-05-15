<?php

namespace App\Models\Module16_Compliance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceControl extends Model
{
    use HasFactory;

    protected $table = 'compliance_controls';

    protected $fillable = [
        'standard_id', 'control_code', 'control_name', 'description',
        'implementation_guide', 'verification_method', 'frequency'
    ];

    public function standard()
    {
        return $this->belongsTo(ComplianceStandard::class, 'standard_id');
    }

    public function checks()
    {
        return $this->hasMany(ComplianceCheck::class);
    }
}