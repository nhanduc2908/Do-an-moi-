<?php

namespace App\Http\Controllers\Api\V1\Module10_EndpointSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\EdrService;

class EdrController extends Controller
{
    protected $edrService;

    public function __construct(EdrService $edrService)
    {
        $this->edrService = $edrService;
    }

    /**
     * Danh sách endpoints
     */
    public function endpoints(Request $request)
    {
        $endpoints = $this->edrService->getEndpoints([
            'status' => $request->status,
            'os' => $request->os,
            'group' => $request->group,
        ]);

        return response()->json([
            'success' => true,
            'data' => $endpoints
        ]);
    }

    /**
     * Chi tiết endpoint
     */
    public function endpointDetail($id)
    {
        $detail = $this->edrService->getEndpointDetail($id);

        return response()->json([
            'success' => true,
            'data' => $detail
        ]);
    }

    /**
     * Alerts
     */
    public function alerts(Request $request)
    {
        $alerts = $this->edrService->getAlerts([
            'severity' => $request->severity,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Threat hunting
     */
    public function threatHunting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'indicator' => 'required|string',
            'type' => 'required|in:hash,ip,domain,file_path,process',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->edrService->threatHunting(
            $request->indicator,
            $request->type
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Isolate endpoint
     */
    public function isolate($id)
    {
        $result = $this->edrService->isolateEndpoint($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Endpoint đã được cách ly' : 'Không thể cách ly endpoint'
        ]);
    }

    /**
     * Reconnect endpoint
     */
    public function reconnect($id)
    {
        $result = $this->edrService->reconnectEndpoint($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Endpoint đã được kết nối lại' : 'Không thể kết nối lại'
        ]);
    }

    /**
     * Collect forensic data
     */
    public function collectForensics(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'data_type' => 'required|array',
            'data_type.*' => 'in:processes,network,registry,files,memory',
        ]);

        $result = $this->edrService->collectForensics($id, $request->data_type);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đang thu thập dữ liệu forensic'
        ]);
    }

    /**
     * Run script on endpoint
     */
    public function runScript(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'script' => 'required|string',
            'timeout' => 'nullable|integer|min=5|max=300',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->edrService->runScript(
            $id,
            $request->script,
            $request->timeout ?? 60
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Thống kê EDR
     */
    public function statistics()
    {
        $stats = $this->edrService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}