<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanExpiredSessionsCommand extends Command
{
    protected $signature = 'security:clean-sessions';
    protected $description = 'Xóa session hết hạn';

    public function handle()
    {
        $this->info('Đang dọn dẹp sessions...');
        // Code clean sessions
        return Command::SUCCESS;
    }
}