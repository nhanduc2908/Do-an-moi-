<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AIGenerateCriteriaCommand extends Command
{
    protected $signature = 'criteria:ai-generate';
    protected $description = 'AI sinh tiêu chí đánh giá';

    public function handle()
    {
        $this->info('AI đang sinh criteria...');
        // Code AI generate
        return Command::SUCCESS;
    }
}