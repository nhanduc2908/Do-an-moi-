<?php

namespace App\Http\Controllers\Api\V1\Module14_IncidentResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ForensicService;

class ForensicController extends Controller
{
    protected $forensicService;

    public function __construct(ForensicService $forensicService)
    {
        $this->forensicService = $forensicService;
    }

    /**
     * Thu thập forensic data
     */
    public function collect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_type' => 'required|in:endpoint,server,container,network',
            'target_id' => 'required|string',
            'data_types' => 'required|array',
            'data_types.*' => 'in:memory,disk,logs,registry,processes,network',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $collectionId = $this->forensicService->startCollection([