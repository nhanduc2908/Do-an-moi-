<?php

namespace App\Listeners;

use App\Events\KeyVerified;
use App\Models\Module02_Encryption\KeyLog;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogKeyVerified implements ShouldQueue
{
    public function handle(KeyVerified $event)
    {
        KeyLog::create([
            'encryption_key_id' => $event->keyId,
            'action' => 'verified',
            'user_id' => $event->userId,
            'ip_address' => request()->ip(),
            'details' => ['purpose' => $event->purpose],
            'performed_at' => now()
        ]);
    }
}