<?php

namespace App\Http\Controllers\Api\V1\Module13_LoggingMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SyslogService;

class SyslogController extends Controller
{
    protected $syslogService;

    public function __construct(SyslogService $syslogService)
    {
        $this->syslogService = $syslogService;
    }

    /**
     * Cấu hình syslog
     */
    public function configure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'server' => 'nullable|string',
            'port' => 'nullable|integer|min=1|max=65535',
            'protocol' => 'nullable|in:udp,tcp',
            'facility' => 'nullable|string',
            'severity' => 'nullable|in:emerg,alert,crit,err,warning,notice,info,debug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $config = $this->syslogService->updateConfig($request->all());

        return response()->json([
            'success' => true,
            'data' => $config,
            'message' => 'Cập nhật cấu hình syslog thành công'
        ]);
    }

    /**
     * Gửi test syslog
     */
    public function sendTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'severity' => 'nullable|in:emerg,alert,crit,err,warning,notice,info,debug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->syslogService->sendTestMessage(
            $request->message,
            $request->severity ?? 'info'
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Test syslog đã được gửi' : 'Gửi test thất bại'
        ]);
    }

    /**
     * Danh sách syslog servers
     */
    public function servers()
    {
        $servers = $this->syslogService->getServers();

        return response()->json([
            'success' => true,
            'data' => $servers
        ]);
    }

    /**
     * Thêm syslog server
     */
    public function addServer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'server' => 'required|string',
            'port' => 'required|integer|min=1|max=65535',
            'protocol' => 'required|in:udp,tcp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $server = $this->syslogService->addServer($request->all());

        return response()->json([
            'success' => true,
            'data' => $server,
            'message' => 'Thêm syslog server thành công'
        ]);
    }

    /**
     * Xóa syslog server
     */
    public function deleteServer($id)
    {
        $result = $this->syslogService->deleteServer($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa server thành công' : 'Xóa thất bại'
        ]);
    }
}