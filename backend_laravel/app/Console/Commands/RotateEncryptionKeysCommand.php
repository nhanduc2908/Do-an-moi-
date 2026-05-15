<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RotateEncryptionKeysCommand extends Command
{
    protected $signature = 'keys:rotate';
    protected $description = 'Xoay vòng khóa mã hóa';

    public function handle()
    {
        $this->info('Đang xoay vòng keys...');
        // Code rotate keys
        return Command::SUCCESS;
    }
}