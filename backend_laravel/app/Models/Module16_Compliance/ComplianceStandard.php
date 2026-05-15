<?php

namespace App\Models\Module16_Compliance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceStandard extends Model
{
    use HasFactory;

    protected $table = 'compliance_standards';

    protected $fillable = [
        'standard_code', 'standard_name', 'version', 'jurisdiction',
        'description', 'is_required', 'effective_date'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'effective_date' => 'datetime',
    ];

    public function controls()
    {
        return $this->hasMany(ComplianceControl::class);
    }
}