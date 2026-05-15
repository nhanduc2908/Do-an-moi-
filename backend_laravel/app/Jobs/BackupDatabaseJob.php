<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $backupType;

    public function __construct($backupType = 'full')
    {
        $this->backupType = $backupType;
    }

    public function handle()
    {
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );
        
        exec($command);
        
        Storage::disk('s3')->put('backups/' . $filename, file_get_contents($path));
        
        // Clean old backups (keep last 30 days)
        $this->cleanOldBackups();
        
        \Log::info('Database backup completed', ['file' => $filename]);
    }

    protected function cleanOldBackups()
    {
        $files = Storage::disk('s3')->files('backups');
        $thirtyDaysAgo = now()->subDays(30);
        
        foreach ($files as $file) {
            $timestamp = Storage::disk('s3')->lastModified($file);
            if ($timestamp <= $thirtyDaysAgo) {
                Storage::disk('s3')->delete($file);
            }
        }
    }
}