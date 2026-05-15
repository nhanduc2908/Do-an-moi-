<?php

namespace App\Models\Module27_ReportAnalytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = 'dashboards';

    protected $fillable = [
        'dashboard_name', 'layout', 'widgets', 'user_id',
        'is_default', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'layout' => 'array',
        'widgets' => 'array',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}