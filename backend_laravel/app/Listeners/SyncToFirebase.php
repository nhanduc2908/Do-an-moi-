<?php

namespace App\Listeners;

use App\Events\DataChanged;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class SyncToFirebase
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials'))
            ->createDatabase();
    }

    public function handle(DataChanged $event)
    {
        try {
            $reference = $this->firebase->getReference($event->path);
            $reference->set($event->data);
            
            Log::info('Synced to Firebase', ['path' => $event->path]);
        } catch (\Exception $e) {
            Log::error('Firebase sync failed', ['error' => $e->getMessage()]);
        }
    }
}