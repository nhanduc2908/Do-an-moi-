<?php

namespace App\Http\Controllers\Api\V1\Module03_WebSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\RceProtectionService;

class RceController extends Controller
{
    protected $rceService;

    public function __construct(RceProtectionService $rceService)
    {
        $this->rceService = $rceService;
    }

    /**
     * Kiểm tra lỗ hổng RCE
     */
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string',
            'context' => 'nullable|in:system,exec,shell_exec,backtick',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->rceService->testCommand(
            $request->command,
            $request->context ?? 'system'
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Escape command
     */
    public function escape(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $escaped = $this->rceService->escapeCommand($request->command);

        return response()->json([
            'success' => true,
            'data' => [
                'original' => $request->command,
                'escaped' => $escaped,
            ]
        ]);
    }

    /**
     * Disable dangerous functions
     */
    public function disableFunctions(Request $request)
    {
        $functions = $this->rceService->getDisabledFunctions();

        return response()->json([
            'success' => true,
            'data' => $functions
        ]);
    }
}