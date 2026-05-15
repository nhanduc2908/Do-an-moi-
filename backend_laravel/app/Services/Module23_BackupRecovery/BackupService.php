<?php

namespace App\Services\Module23_BackupRecovery;

use App\Models\Module23_BackupRecovery\BackupJob;
use App\Models\Module23_BackupRecovery\BackupFile;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public function createBackupJob($data)
    {
        $job = BackupJob::create([
            'job_name' => $data['name'],
            'backup_type' => $data['type'],
            'source_path' => $data['source'],
            'destination_path' => $data['destination'],
            'schedule' => $data['schedule'] ?? null,
            'retention_days' => $data['retention_days'] ?? 30,
            'compression' => $data['compression'] ?? true,
            'encryption' => $data['encryption'] ?? true,
            'status' => 'active',
            'created_by' => auth()->id()
        ]);
        
        if ($job->schedule) {
            $this->scheduleBackup($job);
        }
        
        return $job;
    }

    public function executeBackup($jobId)
    {
        $job = BackupJob::findOrFail($jobId);
        $job->last_run_at = now();
        $job->save();
        
        $backupPath = $this->performBackup($job);
        
        $backupFile = BackupFile::create([
            'backup_job_id' => $job->id,
            'file_name' => basename($backupPath),
            'file_path' => $backupPath,
            'file_size' => filesize($backupPath),
            'checksum' => hash_file('sha256', $backupPath),
            'created_at' => now(),
            'is_verified' => false
        ]);
        
        $this->verifyBackup($backupFile);
        
        return $backupFile;
    }

    protected function performBackup($job)
    {
        $timestamp = now()->format('Ymd_His');
        $backupName = "{$job->job_name}_{$timestamp}.tar.gz";
        $backupPath = storage_path("backups/{$backupName}");
        
        $command = "tar -czf {$backupPath} -C {$job->source_path} .";
        
        if ($job->encryption) {
            $command .= " | openssl enc -aes-256-cbc -salt -out {$backupPath}.enc";
        }
        
        exec($command);
        
        return $job->encryption ? "{$backupPath}.enc" : $backupPath;
    }

    protected function verifyBackup($backupFile)
    {
        $testExtract = storage_path("backups/test_" . uniqid());
        
        $command = "mkdir -p {$testExtract} && tar -xzf {$backupFile->file_path} -C {$testExtract} 2>/dev/null";
        
        if ($job->encryption) {
            $command = "openssl enc -d -aes-256-cbc -in {$backupFile->file_path} | tar -xz -C {$testExtract}";
        }
        
        exec($command, $output, $returnCode);
        
        $backupFile->is_verified = $returnCode === 0;
        $backupFile->save();
        
        // Cleanup test extract
        exec("rm -rf {$testExtract}");
        
        return $backupFile->is_verified;
    }

    protected function scheduleBackup($job)
    {
        // Schedule cron job
        $cronExpression = $this->convertToCron($job->schedule);
        // Add to crontab
    }

    protected function convertToCron($schedule)
    {
        // Convert human schedule to cron expression
        switch ($schedule) {
            case 'daily': return '0 0 * * *';
            case 'weekly': return '0 0 * * 0';
            case 'monthly': return '0 0 1 * *';
            default: return '0 0 * * *';
        }
    }

    public function cleanupOldBackups()
    {
        $jobs = BackupJob::where('status', 'active')->get();
        
        foreach ($jobs as $job) {
            $oldBackups = BackupFile::where('backup_job_id', $job->id)
                ->where('created_at', '<', now()->subDays($job->retention_days))
                ->get();
            
            foreach ($oldBackups as $backup) {
                if (file_exists($backup->file_path)) {
                    unlink($backup->file_path);
                }
                $backup->delete();
            }
        }
    }

    public function getBackupStatus()
    {
        $jobs = BackupJob::all();
        $status = [];
        
        foreach ($jobs as $job) {
            $lastBackup = BackupFile::where('backup_job_id', $job->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $status[] = [
                'job_name' => $job->job_name,
                'status' => $job->status,
                'last_backup' => $lastBackup ? $lastBackup->created_at : null,
                'last_backup_verified' => $lastBackup ? $lastBackup->is_verified : false,
                'next_backup' => $job->next_run_at,
                'backup_count' => BackupFile::where('backup_job_id', $job->id)->count()
            ];
        }
        
        return $status;
    }
}