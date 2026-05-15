<?php

namespace App\Models\Module27_ReportAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'report_name', 'report_type', 'filters', 'format',
        'file_path', 'file_size', 'generated_by', 'generated_at',
        'expires_at', 'download_count'
    ];

    protected $casts = [
        'filters' => 'array',
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function generator()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'generated_by');
    }

    public function schedule()
    {
        return $this->hasOne(ReportSchedule::class);
    }
}