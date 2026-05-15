<?php

namespace App\Http\Controllers\Api\V1\Module05_UrlSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PhishingDetectionService;

class PhishingDetectionController extends Controller
{
    protected $phishingService;

    public function __construct(PhishingDetectionService $phishingService)
    {
        $this->phishingService = $phishingService;
    }

    /**
     * Phát hiện phishing
     */
    public function detect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->phishingService->analyze($request->url);

        return response()->json([
            'success' => true,
            'data' => [
                'is_phishing' => $result['is_phishing'],
                'confidence_score' => $result['confidence'],
                'risk_level' => $result['risk_level'],
                'indicators' => $result['indicators'],
                'similar_domains' => $result['similar_domains'] ?? [],
            ]
        ]);
    }

    /**
     * Báo cáo URL phishing
     */
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->phishingService->reportPhishing(
            $request->url,
            $request->description,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Đã ghi nhận báo cáo phishing'
        ]);
    }

    /**
     * Danh sách phishing đã phát hiện
     */
    public function list(Request $request)
    {
        $list = $this->phishingService->getPhishingList();

        return response()->json([
            'success' => true,
            'data' => $list
        ]);
    }
}