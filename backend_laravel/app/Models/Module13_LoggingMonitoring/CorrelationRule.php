<?php

namespace App\Models\Module13_LoggingMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrelationRule extends Model
{
    use HasFactory;

    protected $table = 'correlation_rules';

    protected $fillable = [
        'rule_name', 'description', 'conditions', 'severity',
        'action', 'is_active', 'priority', 'created_by'
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}