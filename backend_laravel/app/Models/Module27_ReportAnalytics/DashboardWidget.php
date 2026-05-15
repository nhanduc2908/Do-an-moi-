<?php

namespace App\Models\Module27_ReportAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends Model
{
    use HasFactory;

    protected $table = 'dashboard_widgets';

    protected $fillable = [
        'widget_name', 'widget_type', 'configuration',
        'position', 'size', 'refresh_interval', 'is_enabled'
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_enabled' => 'boolean',
    ];
}