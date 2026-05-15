<?php

namespace App\Listeners;

use App\Events\UserActivity;
use App\Models\Module13_LoggingMonitoring\SecurityLog;

class LogUserActivity
{
    public function handle(UserActivity $event)
    {
        SecurityLog::create([
            'event_type' => $event->activityType,
            'severity' => $event->severity ?? 'info',
            'source' => 'web',
            'user_id' => $event->userId,
            'ip_address' => $event->ipAddress ?? request()->ip(),
            'user_agent' => $event->userAgent ?? request()->userAgent(),
            'message' => $event->description,
            'details' => $event->metadata,
            'logged_at' => now()
        ]);
    }
}