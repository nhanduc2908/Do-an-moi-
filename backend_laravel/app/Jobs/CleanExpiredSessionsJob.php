<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Module01_IAM\UserSession;
use Carbon\Carbon;

class CleanExpiredSessionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function handle()
    {
        $expiredSessions = UserSession::where('last_activity', '<', Carbon::now()->subDays(7))
            ->orWhere('is_active', false)
            ->delete();
        
        \Log::info('Cleaned expired sessions', ['count' => $expiredSessions]);
    }
}