<?php

namespace App\Http\Controllers\Api\V1\Module17_PhysicalSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\VisitorTrackingService;

class VisitorTrackingController extends Controller
{
    protected $visitorService;

    public function __construct(VisitorTrackingService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    /**
     * Danh sách visitors
     */
    public function visitors(Request $request)
    {
        $visitors = $this->visitorService->getVisitors([
            'status' => $request->status,
            'date' => $request->date,
            'host' => $request->host,
        ]);

        return response()->json([
            'success' => true,
            'data' => $visitors
        ]);
    }

    /**
     * Register visitor
     */
    public function registerVisitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'email' => 'required|email',
            'phone' => 'required|string',
            'host_id' => 'required|string',
            'purpose' => 'required|string',
            'visit_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $visitor = $this->visitorService->registerVisitor($request->all());

        return response()->json([
            'success' => true,
            'data' => $visitor,
            'message' => 'Đăng ký visitor thành công'
        ]);
    }

    /**
     * Check in
     */
    public function checkIn(Request $request, $id)
    {
        $result = $this->visitorService->checkIn($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Check in thành công' : 'Check in thất bại'
        ]);
    }

    /**
     * Check out
     */
    public function checkOut(Request $request, $id)
    {
        $result = $this->visitorService->checkOut($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Check out thành công' : 'Check out thất bại'
        ]);
    }

    /**
     * Visitor badge
     */
    public function generateBadge($id)
    {
        $badge = $this->visitorService->generateBadge($id);

        return response()->json([
            'success' => true,
            'data' => $badge
        ]);
    }

    /**
     * Visitor analytics
     */
    public function analytics(Request $request)
    {
        $analytics = $this->visitorService->getAnalytics([
            'period' => $request->period ?? 'week',
        ]);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Pre-register visitor
     */
    public function preRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'email' => 'required|email',
            'phone' => 'required|string',
            'host_id' => 'required|string',
            'purpose' => 'required|string',
            'visit_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $visitor = $this->visitorService->preRegister($request->all());

        return response()->json([
            'success' => true,
            'data' => $visitor,
            'message' => 'Pre-registration thành công'
        ]);
    }

    /**
     * Send notification
     */
    public function sendNotification(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,sms',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->visitorService->sendNotification($id, $request->type);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Đã gửi notification' : 'Gửi thất bại'
        ]);
    }
}