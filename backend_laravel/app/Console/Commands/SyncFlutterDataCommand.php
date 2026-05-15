<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncFlutterDataCommand extends Command
{
    protected $signature = 'sync:flutter-data';
    protected $description = 'Đồng bộ dữ liệu với Flutter app';

    public function handle()
    {
        $this->info('Đang đồng bộ dữ liệu Flutter...');
        // Code đồng bộ
        return Command::SUCCESS;
    }
}