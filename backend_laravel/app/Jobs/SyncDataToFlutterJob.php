<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Sync\FlutterSyncService;

class SyncDataToFlutterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $userId;
    protected $dataType;

    public function __construct($userId, $dataType = 'all')
    {
        $this->userId = $userId;
        $this->dataType = $dataType;
    }

    public function handle(FlutterSyncService $syncService)
    {
        $syncService->syncUserData($this->userId, $this->dataType);
        
        \Log::info('Sync to Flutter completed', [
            'user_id' => $this->userId,
            'data_type' => $this->dataType
        ]);
    }
}