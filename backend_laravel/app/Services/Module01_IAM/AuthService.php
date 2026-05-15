<?php

namespace App\Services\Module01_IAM;

use App\Models\Module01_IAM\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
        return ['success' => true];
    }

    public function changePassword($user, $oldPassword, $newPassword)
    {
        if (!Hash::check($oldPassword, $user->password)) {
            return ['success' => false, 'message' => 'Old password is incorrect'];
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return ['success' => true];
    }
}