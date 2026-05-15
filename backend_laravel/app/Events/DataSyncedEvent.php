<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataSyncedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $syncId;
    public $source;
    public $destination;
    public $recordsSynced;
    public $status;
    public $syncedAt;
    public $errors;

    public function __construct($syncId, $source, $destination, $recordsSynced, $status = 'success', $errors = [])
    {
        $this->syncId = $syncId;
        $this->source = $source;
        $this->destination = $destination;
        $this->recordsSynced = $recordsSynced;
        $this->status = $status;
        $this->syncedAt = now();
        $this->errors = $errors;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("sync.{$this->syncId}"),
            new Channel("data.sync"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'sync_id' => $this->syncId,
            'source' => $this->source,
            'destination' => $this->destination,
            'records_synced' => $this->recordsSynced,
            'status' => $this->status,
            'synced_at' => $this->syncedAt->toIso8601String(),
            'errors' => $this->errors,
        ];
    }

    public function broadcastAs(): string
    {
        return 'data.synced';
    }
}