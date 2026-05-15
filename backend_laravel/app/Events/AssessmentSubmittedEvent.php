<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssessmentSubmittedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $assessmentId;
    public $domainId;
    public $submittedBy;
    public $score;
    public $status;
    public $submittedAt;

    public function __construct($assessmentId, $domainId, $submittedBy, $score, $status)
    {
        $this->assessmentId = $assessmentId;
        $this->domainId = $domainId;
        $this->submittedBy = $submittedBy;
        $this->score = $score;
        $this->status = $status;
        $this->submittedAt = now();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("assessment.{$this->assessmentId}"),
            new PrivateChannel("domain.{$this->domainId}"),
            new PrivateChannel("user.{$this->submittedBy}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'assessment_id' => $this->assessmentId,
            'domain_id' => $this->domainId,
            'submitted_by' => $this->submittedBy,
            'score' => $this->score,
            'status' => $this->status,
            'submitted_at' => $this->submittedAt->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'assessment.submitted';
    }
}