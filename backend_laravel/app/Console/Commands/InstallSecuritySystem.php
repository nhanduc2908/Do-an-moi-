<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallSecuritySystem extends Command
{
    protected $signature = 'security:install';
    protected $description = 'Cài đặt hệ thống bảo mật';

    public function handle()
    {
        $this->info('Đang cài đặt hệ thống bảo mật...');
        // Code cài đặt
        return Command::SUCCESS;
    }
}