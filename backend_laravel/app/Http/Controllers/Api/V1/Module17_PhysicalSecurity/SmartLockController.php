<?php

namespace App\Http\Controllers\Api\V1\Module17_PhysicalSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SmartLockService;

class SmartLockController extends Controller
{
    protected $lockService;

    public function __construct(SmartLockService $lockService)
    {
        $this->lockService = $lockService;
    }

    /**
     * Danh sách smart locks
     */
    public function listLocks(Request $request)
    {
        $locks = $this->lockService->getLocks([
            'location' => $request->location,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $locks
        ]);
    }

    /**
     * Register smart lock
     */
    public function registerLock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'location' => 'required|string',
            'mac_address' => 'required|string',
            'model' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $lock = $this->lockService->registerLock($request->all());

        return response()->json([
            'success' => true,
            'data' => $lock,
            'message' => 'Đăng ký smart lock thành công'
        ]);
    }

    /**
     * Unlock remotely
     */
    public function unlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
            'reason' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min=1|max=60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->lockService->unlock(
            $request->lock_id,
            $request->reason,
            $request->duration_minutes ?? 5
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã mở khóa' : 'Không thể mở khóa'
        ]);
    }

    /**
     * Lock remotely
     */
    public function lock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->lockService->lock($request->lock_id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã khóa' : 'Không thể khóa'
        ]);
    }

    /**
     * Access codes
     */
    public function accessCodes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $codes = $this->lockService->getAccessCodes($request->lock_id);

        return response()->json([
            'success' => true,
            'data' => $codes
        ]);
    }

    /**
     * Create access code
     */
    public function createAccessCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
            'code' => 'required|string|size=6',
            'expires_at' => 'nullable|date',
            'max_uses' => 'nullable|integer|min=1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $code = $this->lockService->createAccessCode($request->all());

        return response()->json([
            'success' => true,
            'data' => $code,
            'message' => 'Tạo access code thành công'
        ]);
    }

    /**
     * Delete access code
     */
    public function deleteAccessCode($id)
    {
        $result = $this->lockService->deleteAccessCode($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa access code thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Lock battery status
     */
    public function batteryStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = $this->lockService->getBatteryStatus($request->lock_id);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Lock activity log
     */
    public function activityLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lock_id' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $logs = $this->lockService->getActivityLog($request->all());

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}