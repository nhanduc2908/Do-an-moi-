<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Create10RolesCommand extends Command
{
    protected $signature = 'roles:create-10';
    protected $description = 'Tạo 10 vai trò mặc định';

    public function handle()
    {
        $this->info('Đang tạo 10 roles...');
        // Code tạo roles
        return Command::SUCCESS;
    }
}