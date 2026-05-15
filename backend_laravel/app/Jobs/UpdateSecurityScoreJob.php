<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Module27_ReportAnalytics\SecurityScoreService;

class UpdateSecurityScoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $organizationId;

    public function __construct($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    public function handle(SecurityScoreService $scoreService)
    {
        $score = $scoreService->calculateScore($this->organizationId);
        
        event(new \App\Events\SecurityScoreUpdated($score));
    }
}