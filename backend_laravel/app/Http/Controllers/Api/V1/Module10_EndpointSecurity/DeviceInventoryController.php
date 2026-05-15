<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DeviceInventoryService;

class DeviceInventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(DeviceInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Danh sách thiết bị
     */
    public function index(Request $request)
    {
        $devices = $this->inventoryService->getDevices([
            'type' => $request->type,
            'status' => $request->status,
            'os' => $request->os,
            'group' => $request->group,
        ]);

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Chi tiết thiết bị
     */
    public function show($id)
    {
        $device = $this->inventoryService->getDeviceDetail($id);

        return response()->json([
            'success' => true,
            'data' => $device
        ]);
    }

    /**
     * Phần mềm cài đặt
     */
    public function software($id)
    {
        $software = $this->inventoryService->getInstalledSoftware($id);

        return response()->json([
            'success' => true,
            'data' => $software
        ]);
    }

    /**
     * Hardware info
     */
    public function hardware($id)
    {
        $hardware = $this->inventoryService->getHardwareInfo($id);

        return response()->json([
            'success' => true,
            'data' => $hardware
        ]);
    }

    /**
     * Thêm thiết bị mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'type' => 'required|in:desktop,laptop,server,mobile,printer,network',
            'os' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = $this->inventoryService->addDevice($request->all());

        return response()->json([
            'success' => true,
            'data' => $device,
            'message' => 'Thêm thiết bị thành công'
        ]);
    }

    /**
     * Cập nhật thiết bị
     */
    public function update(Request $request, $id)
    {
        $device = $this->inventoryService->updateDevice($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $device,
            'message' => 'Cập nhật thiết bị thành công'
        ]);
    }

    /**
     * Xóa thiết bị
     */
    public function destroy($id)
    {
        $result = $this->inventoryService->deleteDevice($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa thiết bị thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Export inventory
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'csv';
        $data = $this->inventoryService->exportInventory($format);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}