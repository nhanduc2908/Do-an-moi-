<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SqlInjectionService;

class SqlInjectionController extends Controller
{
    protected $sqlService;

    public function __construct(SqlInjectionService $sqlService)
    {
        $this->sqlService = $sqlService;
    }

    /**
     * Kiểm tra lỗ hổng SQL Injection
     */
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'parameters' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->sqlService->testVulnerability(
            $request->url,
            $request->parameters
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Tường lửa chống SQL Injection
     */
    public function firewall(Request $request)
    {
        $action = $request->action ?? 'status';

        switch ($action) {
            case 'enable':
                $result = $this->sqlService->enableFirewall();
                break;
            case 'disable':
                $result = $this->sqlService->disableFirewall();
                break;
            default:
                $result = $this->sqlService->getFirewallStatus();
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}