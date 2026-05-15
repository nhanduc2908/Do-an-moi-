<?php

namespace App\Http\Controllers\Api\V1\Module17_PhysicalSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\BiometricService;

class BiometricController extends Controller
{
    protected $biometricService;

    public function __construct(BiometricService $biometricService)
    {
        $this->biometricService = $biometricService;
    }

    /**
     * Enroll fingerprint
     */
    public function enrollFingerprint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'fingerprint_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment = $this->biometricService->enrollFingerprint($request->all());

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Enroll fingerprint thành công'
        ]);
    }

    /**
     * Verify fingerprint
     */
    public function verifyFingerprint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'fingerprint_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->biometricService->verifyFingerprint($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'matched' => $result['matched'],
                'score' => $result['score'],
            ]
        ]);
    }

    /**
     * Enroll face
     */
    public function enrollFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'face_image' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment = $this->biometricService->enrollFace(
            $request->user_id,
            $request->file('face_image')
        );

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Enroll face thành công'
        ]);
    }

    /**
     * Verify face
     */
    public function verifyFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'face_image' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->biometricService->verifyFace(
            $request->user_id,
            $request->file('face_image')
        );

        return response()->json([
            'success' => true,
            'data' => [
                'matched' => $result['matched'],
                'confidence' => $result['confidence'],
            ]
        ]);
    }

    /**
     * Iris recognition
     */
    public function verifyIris(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'iris_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->biometricService->verifyIris($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'matched' => $result['matched'],
            ]
        ]);
    }

    /**
     * Delete biometric data
     */
    public function deleteBiometric(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'biometric_type' => 'required|in:fingerprint,face,iris',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->biometricService->deleteBiometricData(
            $request->user_id,
            $request->biometric_type
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa biometric data thành công' : 'Xóa thất bại'
        ]);
    }
}