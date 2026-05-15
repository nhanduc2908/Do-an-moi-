<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Create600CriteriaCommand extends Command
{
    protected $signature = 'criteria:create-600';
    protected $description = 'Tạo 600 tiêu chí đánh giá';

    public function handle()
    {
        $this->info('Đang tạo 600 criteria...');
        // Code tạo criteria
        return Command::SUCCESS;
    }
}