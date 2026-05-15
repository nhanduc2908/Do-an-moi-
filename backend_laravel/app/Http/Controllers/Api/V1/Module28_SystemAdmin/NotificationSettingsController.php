<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationSettingsService;

class NotificationSettingsController extends Controller
{
    protected $settingsService;

    public function __construct(NotificationSettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Channels
     */
    public function channels()
    {
        $channels = $this->settingsService->getChannels();

        return response()->json([
            'success' => true,
            'data' => $channels
        ]);
    }

    /**
     * Update channel
     */
    public function updateChannel(Request $request, $channel)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'config' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->settingsService->updateChannel($channel, $request->all());

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Channel {$channel} updated"
        ]);
    }

    /**
     * Templates
     */
    public function templates(Request $request)
    {
        $templates = $this->settingsService->getTemplates([
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Update template
     */
    public function updateTemplate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $template = $this->settingsService->updateTemplate($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $template,
            'message' => 'Template updated'
        ]);
    }

    /**
     * Rules
     */
    public function rules()
    {
        $rules = $this->settingsService->getRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Create rule
     */
    public function createRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'condition' => 'required|string',
            'channels' => 'required|array',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rule = $this->settingsService->createRule($request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Rule created'
        ]);
    }

    /**
     * Update rule
     */
    public function updateRule(Request $request, $id)
    {
        $rule = $this->settingsService->updateRule($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Rule updated'
        ]);
    }

    /**
     * Delete rule
     */
    public function deleteRule($id)
    {
        $result = $this->settingsService->deleteRule($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Rule deleted' : 'Delete failed'
        ]);
    }

    /**
     * Test notification
     */
    public function testNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|string',
            'recipient' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->settingsService->sendTest($request->channel, $request->recipient);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Test notification sent' : 'Send failed'
        ]);
    }
}