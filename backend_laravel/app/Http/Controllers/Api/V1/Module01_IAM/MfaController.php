<?php

namespace App\Http\Controllers\Api\V1\Module01_IAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Events\MfaEnabledEvent;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Kích hoạt MFA
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        // Tạo secret key
        $secret = $this->google2fa->generateSecretKey();

        // Lưu secret tạm thời
        $user->update([
            'mfa_secret' => $secret,
            'mfa_status' => 'pending',
        ]);

        // Tạo QR code
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return response()->json([
            'success' => true,
            'data' => [
                'secret' => $secret,
                'qr_code_url' => $qrCodeUrl,
                'recovery_codes' => $this->generateRecoveryCodes($user->id),
            ]
        ]);
    }

    /**
     * Xác nhận MFA
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $valid = $this->google2fa->verifyKey($user->mfa_secret, $request->code);

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực không đúng'
            ], 400);
        }

        $user->update([
            'mfa_enabled' => true,
            'mfa_status' => 'active',
            'mfa_verified_at' => now(),
        ]);

        event(new MfaEnabledEvent($user->id, $user->email, 'totp'));

        return response()->json([
            'success' => true,
            'message' => 'MFA đã được kích hoạt'
        ]);
    }

    /**
     * Vô hiệu hóa MFA
     */
    public function disable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng'
            ], 401);
        }

        $user->update([
            'mfa_enabled' => false,
            'mfa_secret' => null,
            'mfa_status' => 'disabled',
            'recovery_codes' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'MFA đã được vô hiệu hóa'
        ]);
    }

    /**
     * Xác thực MFA khi đăng nhập
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
            'session_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $session = Session::where('id', $request->session_id)
            ->where('requires_mfa', true)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session không hợp lệ'
            ], 400);
        }

        $user = User::find($session->user_id);
        $valid = $this->google2fa->verifyKey($user->mfa_secret, $request->code);

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực không đúng'
            ], 401);
        }

        $session->update([
            'mfa_verified' => true,
            'mfa_verified_at' => now(),
            'requires_mfa' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Xác thực MFA thành công'
        ]);
    }

    /**
     * Tạo recovery codes
     */
    private function generateRecoveryCodes($userId)
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5($userId . $i . time()), 0, 8));
        }
        
        $user = User::find($userId);
        $user->update(['recovery_codes' => json_encode($codes)]);
        
        return $codes;
    }
}