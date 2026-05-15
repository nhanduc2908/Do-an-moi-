<?php

namespace App\Http\Controllers\Api\V1\Module19_MobileSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\MdmService;

class MdmController extends Controller
{
    protected $mdmService;

    public function __construct(MdmService $mdmService)
    {
        $this->mdmService = $mdmService;
    }

    /**
     * Danh sách devices
     */
    public function devices(Request $request)
    {
        $devices = $this->mdmService->getDevices([
            'platform' => $request->platform,
            'compliance_status' => $request->compliance_status,
            'os_version' => $request->os_version,
        ]);

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Enroll device
     */
    public function enrollDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_name' => 'required|string|max=100',
            'platform' => 'required|in:ios,android',
            'serial_number' => 'required|string',
            'user_id' => 'required|string',
            'owner_type' => 'required|in:corporate,personal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = $this->mdmService->enrollDevice($request->all());

        return response()->json([
            'success' => true,
            'data' => $device,
            'message' => 'Device đã được enroll'
        ]);
    }

    /**
     * Device detail
     */
    public function deviceDetail($id)
    {
        $device = $this->mdmService->getDeviceDetail($id);

        return response()->json([
            'success' => true,
            'data' => $device
        ]);
    }

    /**
     * Device compliance
     */
    public function deviceCompliance($id)
    {
        $compliance = $this->mdmService->checkCompliance($id);

        return response()->json([
            'success' => true,
            'data' => $compliance
        ]);
    }

    /**
     * Push policy
     */
    public function pushPolicy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->mdmService->pushPolicy($id, $request->policy_id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Policy đã được push' : 'Push policy thất bại'
        ]);
    }
}