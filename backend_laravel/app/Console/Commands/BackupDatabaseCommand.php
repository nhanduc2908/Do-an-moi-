<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Sao lưu cơ sở dữ liệu';

    public function handle()
    {
        $this->info('Đang sao lưu database...');
        // Code backup DB
        return Command::SUCCESS;
    }
}