<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Private user channel for real-time notifications
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Security alerts channel (only users with security roles)
Broadcast::channel('security-alerts', function ($user) {
    return $user->hasAnyRole(['admin', 'super_admin', 'security_manager']);
});

// Assessment updates channel
Broadcast::channel('assessment.{assessmentId}', function ($user, $assessmentId) {
    $assessment = \App\Models\Assessment::find($assessmentId);
    return $assessment && (
        $user->id === $assessment->assigned_to || 
        $user->hasAnyRole(['admin', 'super_admin', 'security_manager'])
    );
});

// Incident updates channel
Broadcast::channel('incident.{incidentId}', function ($user, $incidentId) {
    $incident = \App\Models\Incident::find($incidentId);
    return $incident && (
        $user->id === $incident->assigned_to ||
        $user->id === $incident->reported_by ||
        $user->hasRole('security_manager')
    );
});

// Vulnerability scan progress channel
Broadcast::channel('scan.{scanId}', function ($user, $scanId) {
    return $user->hasAnyRole(['admin', 'security_analyst']);
});

// AI detection channel
Broadcast::channel('ai-detections', function ($user) {
    return $user->hasAnyRole(['admin', 'security_manager', 'security_analyst']);
});

// System metrics channel (admin only)
Broadcast::channel('system-metrics', function ($user) {
    return $user->hasAnyRole(['admin', 'super_admin']);
});

// Sync status channel
Broadcast::channel('sync-status', function ($user) {
    return $user->hasRole('admin');
});

// Compliance audit channel
Broadcast::channel('compliance-audit', function ($user) {
    return $user->hasAnyRole(['admin', 'compliance_officer', 'auditor']);
});