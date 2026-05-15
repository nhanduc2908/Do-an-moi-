<?php

namespace App\Http\Controllers\Api\V1\Module17_PhysicalSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CctvService;

class CctvController extends Controller
{
    protected $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Danh sách cameras
     */
    public function cameras(Request $request)
    {
        $cameras = $this->cctvService->getCameras([
            'location' => $request->location,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $cameras
        ]);
    }

    /**
     * Live stream
     */
    public function liveStream($cameraId)
    {
        $stream = $this->cctvService->getLiveStreamUrl($cameraId);

        return response()->json([
            'success' => true,
            'data' => $stream
        ]);
    }

    /**
     * Recordings
     */
    public function recordings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'camera_id' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $recordings = $this->cctvService->getRecordings($request->all());

        return response()->json([
            'success' => true,
            'data' => $recordings
        ]);
    }

    /**
     * Motion detection alerts
     */
    public function motionAlerts(Request $request)
    {
        $alerts = $this->cctvService->getMotionAlerts([
            'camera_id' => $request->camera_id,
            'start_date' => $request->start_date,
            'severity' => $request->severity,
        ]);

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * AI face detection
     */
    public function faceDetection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $faces = $this->cctvService->detectFaces($request->file('image'));

        return response()->json([
            'success' => true,
            'data' => $faces
        ]);
    }

    /**
     * Add to watchlist
     */
    public function addToWatchlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'face_encoding' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $person = $this->cctvService->addToWatchlist($request->all());

        return response()->json([
            'success' => true,
            'data' => $person,
            'message' => 'Thêm vào watchlist thành công'
        ]);
    }

    /**
     * Export footage
     */
    public function exportFootage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'camera_id' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'format' => 'nullable|in:mp4,avi,mov',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $export = $this->cctvService->exportFootage($request->all());

        return response()->json([
            'success' => true,
            'data' => $export,
            'message' => 'Đang xuất footage'
        ]);
    }

    /**
     * Camera health status
     */
    public function healthStatus()
    {
        $status = $this->cctvService->getHealthStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
}