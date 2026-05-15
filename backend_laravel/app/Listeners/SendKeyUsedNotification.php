<?php

namespace App\Listeners;

use App\Events\KeyUsed;
use App\Mail\KeyUsedNotification;
use Illuminate\Support\Facades\Mail;

class SendKeyUsedNotification
{
    public function handle(KeyUsed $event)
    {
        $user = \App\Models\Module01_IAM\User::find($event->userId);
        
        if ($user && $user->email) {
            Mail::to($user->email)->send(new KeyUsedNotification($event->keyId, $event->purpose));
        }
    }
}