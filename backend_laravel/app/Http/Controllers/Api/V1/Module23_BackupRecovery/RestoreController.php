<?php

namespace App\Http\Controllers\Api\V1\Module23_BackupRecovery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RestorePoint;
use App\Services\RestoreService;

class RestoreController extends Controller
{
    protected $restoreService;

    public function __construct(RestoreService $restoreService)
    {
        $this->restoreService = $restoreService;
    }

    /**
     * Restore points
     */
    public function restorePoints(Request $request)
    {
        $points = RestorePoint::with('backupJob')
            ->when($request->system, function($query, $system) {
                $query->where('system', $system);
            })
            ->when($request->start_date, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $points
        ]);
    }

    /**
     * Create restore point
     */
    public function createRestorePoint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $point = $this->restoreService->createRestorePoint($request->all());

        return response()->json([
            'success' => true,
            'data' => $point,
            'message' => 'Tạo restore point thành công'
        ]);
    }

    /**
     * Restore from point
     */
    public function restoreFromPoint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restore_point_id' => 'required|string',
            'target_location' => 'required|string',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $job = $this->restoreService->restore($request->all());

        return response()->json([
            'success' => true,
            'data' => $job,
            'message' => 'Bắt đầu restore'
        ]);
    }

    /**
     * Restore file
     */
    public function restoreFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_path' => 'required|string',
            'backup_id' => 'required|string',
            'destination' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->restoreService->restoreFile($request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'File restore completed'
        ]);
    }

    /**
     * Restore jobs
     */
    public function restoreJobs(Request $request)
    {
        $jobs = $this->restoreService->getRestoreJobs([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }

    /**
     * Job status
     */
    public function jobStatus($id)
    {
        $status = $this->restoreService->getJobStatus($id);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Cancel restore
     */
    public function cancelRestore($id)
    {
        $result = $this->restoreService->cancelRestore($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã hủy restore job' : 'Hủy thất bại'
        ]);
    }
}