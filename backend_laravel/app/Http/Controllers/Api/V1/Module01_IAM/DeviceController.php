<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Danh sách thiết bị
     */
    public function index(Request $request)
    {
        $devices = Device::where('user_id', $request->user()->id)
            ->orderBy('last_used_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Đăng ký thiết bị mới
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_name' => 'required|string|max:100',
            'device_type' => 'required|in:web,ios,android,desktop',
            'push_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::create([
            'user_id' => $request->user()->id,
            'device_name' => $request->device_name,
            'device_type' => $request->device_type,
            'device_fingerprint' => $request->fingerprint ?? md5($request->userAgent()),
            'push_token' => $request->push_token,
            'is_trusted' => false,
            'last_used_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $device,
            'message' => 'Đăng ký thiết bị thành công'
        ]);
    }

    /**
     * Xóa thiết bị
     */
    public function destroy(Request $request, $id)
    {
        $device = Device::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thiết bị thành công'
        ]);
    }

    /**
     * Trust thiết bị
     */
    public function trust(Request $request, $id)
    {
        $device = Device::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $device->update([
            'is_trusted' => true,
            'trusted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thiết bị đã được trust'
        ]);
    }
}