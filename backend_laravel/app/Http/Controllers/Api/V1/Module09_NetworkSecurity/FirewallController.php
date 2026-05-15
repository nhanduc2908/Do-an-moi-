<?php

namespace App\Http\Controllers\Api\V1\Module09_NetworkSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FirewallService;

class FirewallController extends Controller
{
    protected $firewallService;

    public function __construct(FirewallService $firewallService)
    {
        $this->firewallService = $firewallService;
    }

    /**
     * Lấy cấu hình firewall
     */
    public function getConfig()
    {
        $config = $this->firewallService->getConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Cập nhật cấu hình firewall
     */
    public function updateConfig(Request $request)
    {
        $config = $this->firewallService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình firewall thành công'
        ]);
    }

    /**
     * Danh sách rules
     */
    public function listRules(Request $request)
    {
        $rules = $this->firewallService->getRules();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Thêm rule mới
     */
    public function addRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'action' => 'required|in:allow,deny,reject',
            'protocol' => 'required|in:tcp,udp,icmp,all',
            'source_ip' => 'nullable|string',
            'destination_ip' => 'nullable|string',
            'source_port' => 'nullable|integer|min=1|max=65535',
            'destination_port' => 'nullable|integer|min=1|max=65535',
            'direction' => 'required|in:inbound,outbound,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rule = $this->firewallService->addRule($request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Thêm rule thành công'
        ]);
    }

    /**
     * Xóa rule
     */
    public function deleteRule($id)
    {
        $result = $this->firewallService->deleteRule($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa rule thành công' : 'Xóa rule thất bại'
        ]);
    }

    /**
     * Kích hoạt firewall
     */
    public function enable()
    {
        $result = $this->firewallService->enable();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Firewall đã được kích hoạt' : 'Không thể kích hoạt firewall'
        ]);
    }

    /**
     * Vô hiệu hóa firewall
     */
    public function disable()
    {
        $result = $this->firewallService->disable();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Firewall đã bị vô hiệu hóa' : 'Không thể vô hiệu hóa firewall'
        ]);
    }

    /**
     * Thống kê
     */
    public function statistics()
    {
        $stats = $this->firewallService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}