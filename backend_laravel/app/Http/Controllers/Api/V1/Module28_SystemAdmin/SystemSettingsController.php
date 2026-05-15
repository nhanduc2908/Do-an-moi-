<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SystemSetting;
use App\Services\SystemSettingsService;

class SystemSettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SystemSettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Danh sách settings
     */
    public function index()
    {
        $settings = $this->settingsService->getAllSettings();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Lấy setting theo key
     */
    public function get($key)
    {
        $value = $this->settingsService->getSetting($key);

        return response()->json([
            'success' => true,
            'data' => ['key' => $key, 'value' => $value]
        ]);
    }

    /**
     * Cập nhật setting
     */
    public function update(Request $request, $key)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $setting = $this->settingsService->updateSetting($key, $request->value);

        return response()->json([
            'success' => true,
            'data' => $setting,
            'message' => 'Setting updated'
        ]);
    }

    /**
     * Reset to default
     */
    public function resetToDefault(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->settingsService->resetToDefault($request->key);

        return response()->json([
            'success' => true,
            'message' => $request->key ? 'Setting reset' : 'All settings reset'
        ]);
    }

    /**
     * Backup settings
     */
    public function backupSettings()
    {
        $backup = $this->settingsService->backup();

        return response()->json([
            'success' => true,
            'data' => $backup
        ]);
    }

    /**
     * Restore settings
     */
    public function restoreSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_file' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->settingsService->restore($request->backup_file);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Settings restored' : 'Restore failed'
        ]);
    }

    /**
     * Settings audit
     */
    public function settingsAudit(Request $request)
    {
        $audit = $this->settingsService->getAuditLog([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $audit
        ]);
    }
}