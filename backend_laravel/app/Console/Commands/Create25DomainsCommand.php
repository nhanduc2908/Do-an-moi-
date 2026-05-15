<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Create25DomainsCommand extends Command
{
    protected $signature = 'domains:create-25';
    protected $description = 'Tạo 25 domain bảo mật';

    public function handle()
    {
        $this->info('Đang tạo 25 domains...');
        // Code tạo domains
        return Command::SUCCESS;
    }
}