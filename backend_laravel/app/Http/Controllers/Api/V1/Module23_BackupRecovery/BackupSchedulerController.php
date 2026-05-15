<?php

namespace App\Http\Controllers\Api\V1\Module23_BackupRecovery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BackupJob;
use App\Services\BackupSchedulerService;

class BackupSchedulerController extends Controller
{
    protected $schedulerService;

    public function __construct(BackupSchedulerService $schedulerService)
    {
        $this->schedulerService = $schedulerService;
    }

    /**
     * Danh sách jobs
     */
    public function jobs(Request $request)
    {
        $jobs = BackupJob::with('lastExecution')
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->is_active, function($query, $isActive) {
                $query->where('is_active', $isActive);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }

    /**
     * Tạo job
     */
    public function createJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'type' => 'required|in:full,incremental,differential',
            'schedule' => 'required|string', // cron expression
            'targets' => 'required|array',
            'retention_days' => 'required|integer|min=1|max=365',
            'compression' => 'nullable|boolean',
            'encryption' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $job = $this->schedulerService->createJob($request->all());

        return response()->json([
            'success' => true,
            'data' => $job,
            'message' => 'Tạo backup job thành công'
        ]);
    }

    /**
     * Update job
     */
    public function updateJob(Request $request, $id)
    {
        $job = BackupJob::findOrFail($id);

        $job->update($request->only([
            'name', 'schedule', 'retention_days', 'compression', 'encryption', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'data' => $job,
            'message' => 'Cập nhật backup job thành công'
        ]);
    }

    /**
     * Delete job
     */
    public function deleteJob($id)
    {
        $job = BackupJob::findOrFail($id);
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa backup job thành công'
        ]);
    }

    /**
     * Run now
     */
    public function runNow($id)
    {
        $job = BackupJob::findOrFail($id);
        
        $execution = $this->schedulerService->runJob($job);

        return response()->json([
            'success' => true,
            'data' => $execution,
            'message' => 'Đã bắt đầu chạy backup job'
        ]);
    }

    /**
     * Execution history
     */
    public function executionHistory(Request $request)
    {
        $history = $this->schedulerService->getExecutionHistory([
            'job_id' => $request->job_id,
            'status' => $request->status,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}