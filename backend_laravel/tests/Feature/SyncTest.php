<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Sync\FirebaseSyncService;
use App\Services\Sync\FlutterSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_to_flutter()
    {
        $service = new FlutterSyncService();
        
        $result = $service->syncUserData(1, 'all');
        
        $this->assertTrue($result['success'] ?? false);
    }

    public function test_sync_to_firebase()
    {
        $service = new FirebaseSyncService();
        
        $result = $service->syncData('assessments', ['id' => 1, 'score' => 85]);
        
        $this->assertTrue($result);
    }
}