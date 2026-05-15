<?php

namespace App\Http\Controllers\Api\V1\Module14_IncidentResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Gửi thông báo incident
     */
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'incident_id' => 'required|string',
            'channels' => 'required|array',
            'channels.*' => 'in:email,sms,slack,teams,webhook',
            'recipients' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->notificationService->sendIncidentNotification(
            $request->incident_id,
            $request->channels,
            $request->recipients
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã gửi thông báo'
        ]);
    }

    /**
     * Cấu hình notification channels
     */
    public function configureChannels(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|array',
            'sms' => 'nullable|array',
            'slack' => 'nullable|array',
            'teams' => 'nullable|array',
            'webhook' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->notificationService->configureChannels($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình channels thành công'
        ]);
    }

    /**
     * Danh sách notification templates
     */
    public function templates()
    {
        $templates = $this->notificationService->getTemplates();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Tạo notification template
     */
    public function createTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'subject' => 'required|string',
            'body' => 'required|string',
            'channel' => 'required|in:email,sms,slack,teams',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $template = $this->notificationService->createTemplate($request->all());

        return response()->json([
            'success' => true,
            'data' => $template,
            'message' => 'Tạo template thành công'
        ]);
    }

    /**
     * Notification history
     */
    public function history(Request $request)
    {
        $history = $this->notificationService->getNotificationHistory([
            'incident_id' => $request->incident_id,
            'channel' => $request->channel,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Test notification channel
     */
    public function testChannel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|in:email,sms,slack,teams,webhook',
            'recipient' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->notificationService->testChannel(
            $request->channel,
            $request->recipient
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Test notification thành công' : 'Test notification thất bại'
        ]);
    }
}