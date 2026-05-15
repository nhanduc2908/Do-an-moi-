<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SecurityIncidentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incidentId;
    public $type;
    public $severity;
    public $description;
    public $source;
    public $reportedAt;
    public $status;

    public function __construct($incidentId, $type, $severity, $description, $source)
    {
        $this->incidentId = $incidentId;
        $this->type = $type;
        $this->severity = $severity;
        $this->description = $description;
        $this->source = $source;
        $this->reportedAt = now();
        $this->status = 'reported';
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("incident.{$this->incidentId}"),
            new Channel("security.incidents"),
        ];

        if ($this->severity === 'critical' || $this->severity === 'high') {
            $channels[] = new PrivateChannel("admin.alerts");
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'incident_id' => $this->incidentId,
            'type' => $this->type,
            'severity' => $this->severity,
            'description' => $this->description,
            'source' => $this->source,
            'reported_at' => $this->reportedAt->toIso8601String(),
            'status' => $this->status,
        ];
    }

    public function broadcastAs(): string
    {
        return 'security.incident';
    }
}