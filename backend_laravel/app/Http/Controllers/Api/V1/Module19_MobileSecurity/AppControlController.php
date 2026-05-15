<?php

namespace App\Http\Controllers\Api\V1\Module19_MobileSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AppControlService;

class AppControlController extends Controller
{
    protected $appControlService;

    public function __construct(AppControlService $appControlService)
    {
        $this->appControlService = $appControlService;
    }

    /**
     * Danh sách allowed apps
     */
    public function allowedApps(Request $request)
    {
        $apps = $this->appControlService->getAllowedApps([
            'platform' => $request->platform,
        ]);

        return response()->json([
            'success' => true,
            'data' => $apps
        ]);
    }

    /**
     * Danh sách blocked apps
     */
    public function blockedApps(Request $request)
    {
        $apps = $this->appControlService->getBlockedApps([
            'platform' => $request->platform,
        ]);

        return response()->json([
            'success' => true,
            'data' => $apps
        ]);
    }

    /**
     * Add allowed app
     */
    public function addAllowedApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string',
            'app_name' => 'required|string',
            'platform' => 'required|in:ios,android',
            'version' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $app = $this->appControlService->addAllowedApp($request->all());

        return response()->json([
            'success' => true,
            'data' => $app,
            'message' => 'Thêm app vào allowed list thành công'
        ]);
    }

    /**
     * Block app
     */
    public function blockApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string',
            'app_name' => 'required|string',
            'platform' => 'required|in:ios,android',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $app = $this->appControlService->blockApp($request->all());

        return response()->json([
            'success' => true,
            'data' => $app,
            'message' => 'App đã bị chặn'
        ]);
    }

    /**
     * Remove from blacklist
     */
    public function unblockApp($id)
    {
        $result = $this->appControlService->unblockApp($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Gỡ chặn app thành công' : 'Gỡ chặn thất bại'
        ]);
    }

    /**
     * Installed apps on device
     */
    public function installedApps($deviceId)
    {
        $apps = $this->appControlService->getInstalledApps($deviceId);

        return response()->json([
            'success' => true,
            'data' => $apps
        ]);
    }

    /**
     * Scan device for malicious apps
     */
    public function scanDevice($deviceId)
    {
        $scan = $this->appControlService->scanForMaliciousApps($deviceId);

        return response()->json([
            'success' => true,
            'data' => $scan
        ]);
    }

    /**
     * Remote uninstall app
     */
    public function remoteUninstall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'package_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->appControlService->remoteUninstall(
            $request->device_id,
            $request->package_name
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã gửi lệnh gỡ cài đặt' : 'Gửi lệnh thất bại'
        ]);
    }
}