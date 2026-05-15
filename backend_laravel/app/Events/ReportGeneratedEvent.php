<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportGeneratedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reportId;
    public $type;
    public $generatedBy;
    public $format;
    public $filePath;
    public $generatedAt;
    public $size;

    public function __construct($reportId, $type, $generatedBy, $format, $filePath, $size = null)
    {
        $this->reportId = $reportId;
        $this->type = $type;
        $this->generatedBy = $generatedBy;
        $this->format = $format;
        $this->filePath = $filePath;
        $this->generatedAt = now();
        $this->size = $size;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("report.{$this->reportId}"),
            new PrivateChannel("user.{$this->generatedBy}"),
            new Channel("reports"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'report_id' => $this->reportId,
            'type' => $this->type,
            'generated_by' => $this->generatedBy,
            'format' => $this->format,
            'file_path' => $this->filePath,
            'generated_at' => $this->generatedAt->toIso8601String(),
            'size' => $this->size,
        ];
    }

    public function broadcastAs(): string
    {
        return 'report.generated';
    }
}