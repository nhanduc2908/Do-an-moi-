<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestKeyVerificationCommand extends Command
{
    protected $signature = 'keys:test-verification';
    protected $description = 'Kiểm tra xác thực khóa';

    public function handle()
    {
        $this->info('Đang kiểm tra xác thực key...');
        // Code test verification
        return Command::SUCCESS;
    }
}