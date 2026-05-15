<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\BackupSettingsService;

class BackupSettingsController extends Controller
{
    protected $backupService;

    public function __construct(BackupSettingsService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Get config
     */
    public function getConfig()
    {
        $config = $this->backupService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Update config
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_path' => 'nullable|string',
            'retention_days' => 'nullable|integer|min:1|max:365',
            'compression_enabled' => 'nullable|boolean',
            'encryption_enabled' => 'nullable|boolean',
            'schedule_cron' => 'nullable|string',
            'max_backup_size_gb' => 'nullable|integer|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->backupService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Backup config updated'
        ]);
    }

    /**
     * Manual backup
     */
    public function manualBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|in:full,database,files',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $backup = $this->backupService->runManualBackup($request->type ?? 'full');

        return response()->json([
            'success' => true,
            'data' => $backup,
            'message' => 'Manual backup started'
        ]);
    }

    /**
     * Backup history
     */
    public function backupHistory(Request $request)
    {
        $history = $this->backupService->getBackupHistory([
            'type' => $request->type,
            'status' => $request->status,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Restore backup
     */
    public function restoreBackup(Request $request, $backupId)
    {
        $validator = Validator::make($request->all(), [
            'target_path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->backupService->restoreBackup($backupId, $request->target_path);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Restore started' : 'Restore failed'
        ]);
    }

    /**
     * Verify backup
     */
    public function verifyBackup($backupId)
    {
        $result = $this->backupService->verifyBackup($backupId);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Delete backup
     */
    public function deleteBackup($backupId)
    {
        $result = $this->backupService->deleteBackup($backupId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Backup deleted' : 'Delete failed'
        ]);
    }
}