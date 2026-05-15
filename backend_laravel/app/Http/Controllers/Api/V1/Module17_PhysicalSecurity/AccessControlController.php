<?php

namespace App\Http\Controllers\Api\V1\Module17_PhysicalSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AccessControlService;

class AccessControlController extends Controller
{
    protected $accessService;

    public function __construct(AccessControlService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * Danh sách access points
     */
    public function accessPoints(Request $request)
    {
        $points = $this->accessService->getAccessPoints([
            'location' => $request->location,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $points
        ]);
    }

    /**
     * Access logs
     */
    public function accessLogs(Request $request)
    {
        $logs = $this->accessService->getAccessLogs([
            'user_id' => $request->user_id,
            'access_point' => $request->access_point,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Grant access
     */
    public function grantAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'access_point_id' => 'required|string',
            'schedule' => 'nullable|array',
            'permanent' => 'nullable|boolean',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $grant = $this->accessService->grantAccess($request->all());

        return response()->json([
            'success' => true,
            'data' => $grant,
            'message' => 'Cấp quyền truy cập thành công'
        ]);
    }

    /**
     * Revoke access
     */
    public function revokeAccess($id)
    {
        $result = $this->accessService->revokeAccess($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Thu hồi quyền truy cập thành công' : 'Thu hồi thất bại'
        ]);
    }

    /**
     * Access schedules
     */
    public function schedules()
    {
        $schedules = $this->accessService->getSchedules();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Create schedule
     */
    public function createSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'time_rules' => 'required|array',
            'time_rules.*.day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time_rules.*.start_time' => 'required|date_format:H:i',
            'time_rules.*.end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = $this->accessService->createSchedule($request->all());

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Tạo schedule thành công'
        ]);
    }

    /**
     * Unauthorized access alerts
     */
    public function unauthorizedAlerts(Request $request)
    {
        $alerts = $this->accessService->getUnauthorizedAlerts([
            'severity' => $request->severity,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Door status
     */
    public function doorStatus()
    {
        $status = $this->accessService->getDoorStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Lock/Unlock door
     */
    public function controlDoor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'door_id' => 'required|string',
            'action' => 'required|in:lock,unlock',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->accessService->controlDoor(
            $request->door_id,
            $request->action
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? "Đã {$request->action} cửa" : 'Thao tác thất bại'
        ]);
    }
}