<?php

namespace App\Http\Controllers\Api\V1\Module23_BackupRecovery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CloudBackupService;

class CloudBackupController extends Controller
{
    protected $cloudService;

    public function __construct(CloudBackupService $cloudService)
    {
        $this->cloudService = $cloudService;
    }

    /**
     * Providers
     */
    public function providers()
    {
        $providers = $this->cloudService->getProviders();

        return response()->json([
            'success' => true,
            'data' => $providers
        ]);
    }

    /**
     * Configure provider
     */
    public function configureProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:aws_s3,azure_blob,gcs,wasabi,backblaze',
            'credentials' => 'required|array',
            'bucket_name' => 'required|string',
            'region' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->cloudService->configure($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cloud provider configured'
        ]);
    }

    /**
     * Sync to cloud
     */
    public function syncToCloud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_id' => 'required|string',
            'provider_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sync = $this->cloudService->sync($request->backup_id, $request->provider_id);

        return response()->json([
            'success' => true,
            'data' => $sync,
            'message' => 'Syncing to cloud'
        ]);
    }

    /**
     * Storage usage
     */
    public function storageUsage(Request $request)
    {
        $usage = $this->cloudService->getStorageUsage();

        return response()->json([
            'success' => true,
            'data' => $usage
        ]);
    }

    /**
     * Retrieve from cloud
     */
    public function retrieveFromCloud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_id' => 'required|string',
            'provider_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $retrieve = $this->cloudService->retrieve($request->backup_id, $request->provider_id);

        return response()->json([
            'success' => true,
            'data' => $retrieve,
            'message' => 'Retrieving from cloud'
        ]);
    }
}