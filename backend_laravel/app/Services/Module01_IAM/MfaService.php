<?php

namespace App\Services\Module01_IAM;

use App\Models\Module01_IAM\MfaToken;
use App\Models\Module01_IAM\User;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class MfaService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecret($user)
    {
        $secret = $this->google2fa->generateSecretKey();
        $user->mfa_secret = $secret;
        $user->save();

        return [
            'secret' => $secret,
            'qr_code' => $this->google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            )
        ];
    }

    public function verifyCode($secret, $code)
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    public function enableMfa($user, $code)
    {
        if ($this->verifyCode($user->mfa_secret, $code)) {
            $user->mfa_enabled = true;
            $user->save();
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Invalid code'];
    }

    public function disableMfa($user)
    {
        $user->mfa_enabled = false;
        $user->mfa_secret = null;
        $user->save();

        return ['success' => true];
    }
}