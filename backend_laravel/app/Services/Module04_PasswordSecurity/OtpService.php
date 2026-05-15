<?php

namespace App\Services\Module04_PasswordSecurity;

use App\Models\Module04_PasswordSecurity\OtpCode;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generateOtp($userId, $type = 'email')
    {
        $code = random_int(100000, 999999);
        
        OtpCode::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->delete();

        return OtpCode::create([
            'user_id' => $userId,
            'code' => Hash::make($code),
            'type' => $type,
            'expires_at' => now()->addMinutes(10)
        ]);
    }

    public function verifyOtp($userId, $code, $type = 'email')
    {
        $otp = OtpCode::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($otp && Hash::check($code, $otp->code)) {
            $otp->is_used = true;
            $otp->save();
            return true;
        }

        return false;
    }

    public function sendOtpViaEmail($user, $otp)
    {
        Mail::send('emails.otp', ['code' => $otp], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your OTP Code')
                    ->from('noreply@security.com', 'Security System');
        });
    }

    public function sendOtpViaSms($phoneNumber, $otp)
    {
        // Integrate with SMS provider
        // Example: Twilio, Vonage, etc.
    }
}