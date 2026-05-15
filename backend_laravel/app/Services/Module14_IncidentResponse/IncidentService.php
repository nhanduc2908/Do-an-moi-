<?php

namespace App\Services\Module14_IncidentResponse;

use App\Models\Module14_IncidentResponse\Incident;
use App\Models\Module14_IncidentResponse\IncidentComment;

class IncidentService
{
    public function createIncident($data)
    {
        $incident = Incident::create([
            'incident_code' => $this->generateIncidentCode(),
            'title' => $data['title'],
            'description' => $data['description'],
            'severity' => $data['severity'],
            'status' => 'open',
            'category' => $data['category'],
            'detected_at' => $data['detected_at'] ?? now(),
            'reported_by' => $data['reported_by'] ?? auth()->id(),
            'assigned_to' => $data['assigned_to'] ?? null
        ]);
        
        $this->notifyStakeholders($incident);
        
        return $incident;
    }

    public function assignIncident($incidentId, $userId)
    {
        $incident = Incident::findOrFail($incidentId);
        $incident->assigned_to = $userId;
        $incident->responded_at = now();
        $incident->save();
        
        return $incident;
    }

    public function updateStatus($incidentId, $status, $comment = null)
    {
        $incident = Incident::findOrFail($incidentId);
        $incident->status = $status;
        
        if ($status === 'resolved') {
            $incident->resolved_at = now();
        }
        
        $incident->save();
        
        if ($comment) {
            $this->addComment($incidentId, $comment);
        }
        
        return $incident;
    }

    public function addComment($incidentId, $comment)
    {
        return IncidentComment::create([
            'incident_id' => $incidentId,
            'user_id' => auth()->id(),
            'comment' => $comment,
            'created_at' => now()
        ]);
    }

    protected function generateIncidentCode()
    {
        $prefix = 'INC';
        $year = date('Y');
        $sequence = Incident::whereYear('created_at', $year)->count() + 1;
        
        return sprintf("%s-%s-%04d", $prefix, $year, $sequence);
    }

    protected function notifyStakeholders($incident)
    {
        // Notify security team via email/SMS
        $notifiable = config('incident.responders', []);
        
        foreach ($notifiable as $user) {
            // Send notification
        }
    }

    public function getIncidentTimeline($incidentId)
    {
        $incident = Incident::with('comments', 'evidences', 'recoveryLogs')->findOrFail($incidentId);
        
        $timeline = [];
        
        // Add incident creation
        $timeline[] = [
            'type' => 'incident_created',
            'timestamp' => $incident->created_at,
            'user' => $incident->reporter->name ?? 'System',
            'details' => 'Incident created'
        ];
        
        // Add assignment
        if ($incident->assigned_to) {
            $timeline[] = [
                'type' => 'incident_assigned',
                'timestamp' => $incident->responded_at,
                'user' => $incident->assignee->name ?? 'Unknown',
                'details' => 'Incident assigned'
            ];
        }
        
        // Add comments
        foreach ($incident->comments as $comment) {
            $timeline[] = [
                'type' => 'comment',
                'timestamp' => $comment->created_at,
                'user' => $comment->user->name ?? 'Unknown',
                'details' => $comment->comment
            ];
        }
        
        // Sort by timestamp
        usort($timeline, function($a, $b) {
            return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
        });
        
        return $timeline;
    }
}