<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMasterKeysCommand extends Command
{
    protected $signature = 'keys:generate-master';
    protected $description = 'Tạo master keys mã hóa';

    public function handle()
    {
        $this->info('Đang tạo master keys...');
        // Code tạo keys
        return Command::SUCCESS;
    }
}