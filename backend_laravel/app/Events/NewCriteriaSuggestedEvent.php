<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCriteriaSuggestedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $criteriaId;
    public $suggestedBy;
    public $domainId;
    public $title;
    public $description;
    public $status;
    public $suggestedAt;

    public function __construct($criteriaId, $suggestedBy, $domainId, $title, $description)
    {
        $this->criteriaId = $criteriaId;
        $this->suggestedBy = $suggestedBy;
        $this->domainId = $domainId;
        $this->title = $title;
        $this->description = $description;
        $this->status = 'pending';
        $this->suggestedAt = now();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("criteria.{$this->criteriaId}"),
            new PrivateChannel("domain.{$this->domainId}"),
            new Channel("criteria.suggestions"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'criteria_id' => $this->criteriaId,
            'suggested_by' => $this->suggestedBy,
            'domain_id' => $this->domainId,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'suggested_at' => $this->suggestedAt->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'criteria.suggested';
    }
}