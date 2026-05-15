<?php

namespace App\Http\Controllers\Api\V1\Module07_SourceCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DependencyScanService;

class DependencyScanController extends Controller
{
    protected $dependencyService;

    public function __construct(DependencyScanService $dependencyService)
    {
        $this->dependencyService = $dependencyService;
    }

    /**
     * Quét dependencies
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'composer_lock' => 'nullable|file|mimes:json',
            'package_json' => 'nullable|file|mimes:json',
            'requirements_txt' => 'nullable|file|mimes:txt',
            'go_mod' => 'nullable|file|mimes:mod',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $files = [];
        if ($request->hasFile('composer_lock')) {
            $files['composer_lock'] = $request->file('composer_lock');
        }
        if ($request->hasFile('package_json')) {
            $files['package_json'] = $request->file('package_json');
        }
        if ($request->hasFile('requirements_txt')) {
            $files['requirements_txt'] = $request->file('requirements_txt');
        }
        if ($request->hasFile('go_mod')) {
            $files['go_mod'] = $request->file('go_mod');
        }

        $result = $this->dependencyService->scan($files);

        return response()->json([
            'success' => true,
            'data' => [
                'vulnerable_packages' => $result['vulnerable'],
                'total_packages' => $result['total'],
                'critical_count' => $result['critical_count'],
                'high_count' => $result['high_count'],
                'medium_count' => $result['medium_count'],
                'low_count' => $result['low_count'],
                'details' => $result['details'],
            ]
        ]);
    }

    /**
     * Kiểm tra package cụ thể
     */
    public function checkPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'version' => 'required|string',
            'ecosystem' => 'required|in:composer,npm,pypi,go,maven,rubygems',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->dependencyService->checkPackage(
            $request->name,
            $request->version,
            $request->ecosystem
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Cập nhật khuyến nghị
     */
    public function getRecommendations(Request $request)
    {
        $recommendations = $this->dependencyService->getRecommendations();

        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }
}