<?php

namespace App\Models\Module27_ReportAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSchedule extends Model
{
    use HasFactory;

    protected $table = 'report_schedules';

    protected $fillable = [
        'report_id', 'frequency', 'time', 'day_of_week',
        'day_of_month', 'recipients', 'is_active',
        'last_generated_at', 'next_generation_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'is_active' => 'boolean',
        'last_generated_at' => 'datetime',
        'next_generation_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}