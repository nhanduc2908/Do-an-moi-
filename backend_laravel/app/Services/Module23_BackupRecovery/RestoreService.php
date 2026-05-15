<?php

namespace App\Services\Module23_BackupRecovery;

use App\Models\Module23_BackupRecovery\RestoreLog;
use App\Models\Module23_BackupRecovery\BackupFile;

class RestoreService
{
    public function restoreFromBackup($backupFileId, $restorePath, $reason)
    {
        $backupFile = BackupFile::findOrFail($backupFileId);
        
        $restoreLog = RestoreLog::create([
            'backup_file_id' => $backupFileId,
            'restored_by' => auth()->id(),
            'restored_to' => $restorePath,
            'restore_reason' => $reason,
            'restored_at' => now(),
            'status' => 'in_progress'
        ]);
        
        $startTime = microtime(true);
        
        try {
            $this->performRestore($backupFile, $restorePath);
            $success = true;
            $error = null;
        } catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
        }
        
        $duration = microtime(true) - $startTime;
        
        $restoreLog->status = $success ? 'completed' : 'failed';
        $restoreLog->duration_minutes = round($duration / 60, 2);
        $restoreLog->errors = $error;
        $restoreLog->save();
        
        if ($success) {
            $this->verifyRestore($restorePath, $backupFile);
        }
        
        return $restoreLog;
    }

    protected function performRestore($backupFile, $restorePath)
    {
        $command = "mkdir -p {$restorePath} && tar -xzf {$backupFile->file_path} -C {$restorePath}";
        
        if ($backupFile->job && $backupFile->job->encryption) {
            $command = "openssl enc -d -aes-256-cbc -in {$backupFile->file_path} | tar -xz -C {$restorePath}";
        }
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception("Restore failed with code: {$returnCode}");
        }
    }

    protected function verifyRestore($restorePath, $backupFile)
    {
        // Verify restored data integrity
        $checksum = $this->calculateDirectoryChecksum($restorePath);
        
        return $checksum === $backupFile->checksum;
    }

    protected function calculateDirectoryChecksum($path)
    {
        $command = "find {$path} -type f -exec sha256sum {} \\; | sort | sha256sum | cut -d' ' -f1";
        return trim(shell_exec($command));
    }

    public function getRestoreHistory($backupFileId = null)
    {
        $query = RestoreLog::with('backupFile', 'restorer');
        
        if ($backupFileId) {
            $query->where('backup_file_id', $backupFileId);
        }
        
        return $query->orderBy('restored_at', 'desc')->get();
    }

    public function getRestoreMetrics($days = 30)
    {
        $restores = RestoreLog::where('restored_at', '>=', now()->subDays($days))->get();
        
        return [
            'total_restores' => $restores->count(),
            'successful' => $restores->where('status', 'completed')->count(),
            'failed' => $restores->where('status', 'failed')->count(),
            'success_rate' => $restores->count() > 0 
                ? ($restores->where('status', 'completed')->count() / $restores->count()) * 100 
                : 100,
            'average_restore_time' => $restores->avg('duration_minutes'),
            'by_reason' => $restores->groupBy('restore_reason')->map->count()
        ];
    }
}