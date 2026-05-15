<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailySecurityReport extends Command
{
    protected $signature = 'security:daily-report';
    protected $description = 'Gửi báo cáo bảo mật hàng ngày';

    public function handle()
    {
        $this->info('Đang gửi báo cáo bảo mật...');
        // Code gửi report
        return Command::SUCCESS;
    }
}