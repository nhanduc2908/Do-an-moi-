<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Module26_AIEngine\AICriteriaGeneratorService;

class ProcessAISuggestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $suggestionId;
    protected $domain;
    protected $requirements;

    public function __construct($suggestionId, $domain, $requirements)
    {
        $this->suggestionId = $suggestionId;
        $this->domain = $domain;
        $this->requirements = $requirements;
    }

    public function handle(AICriteriaGeneratorService $aiService)
    {
        $suggestion = $aiService->generateCriteria($this->domain, $this->requirements);
        
        event(new \App\Events\AISuggestionProcessed($suggestion));
    }
}