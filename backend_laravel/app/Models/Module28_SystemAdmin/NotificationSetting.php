<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'notification_settings';

    protected $fillable = [
        'channel', 'events', 'recipients', 'template',
        'is_active', 'rate_limit', 'created_by'
    ];

    protected $casts = [
        'events' => 'array',
        'recipients' => 'array',
        'is_active' => 'boolean',
    ];
}