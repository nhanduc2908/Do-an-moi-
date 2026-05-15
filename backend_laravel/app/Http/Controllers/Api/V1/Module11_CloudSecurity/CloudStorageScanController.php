<?php

namespace App\Http\Controllers\Api\V1\Module11_CloudSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CloudStorageScanService;

class CloudStorageScanController extends Controller
{
    protected $storageScanService;

    public function __construct(CloudStorageScanService $storageScanService)
    {
        $this->storageScanService = $storageScanService;
    }

    /**
     * Quét storage
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:aws,azure,gcp',
            'bucket_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' =>