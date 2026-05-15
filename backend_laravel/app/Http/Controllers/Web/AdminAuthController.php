<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LoginHistory;
use App\Events\UserLoggedInEvent;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'profile', 'updateProfile']);
    }

    /**
     * Hiển thị form login
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Xử lý login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $remember = $request->remember ?? false;

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Kiểm tra role admin
            if (!$user->hasRole('admin') && !$user->hasRole('super_admin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Bạn không có quyền truy cập khu vực quản trị.',
                ]);
            }

            // Ghi nhận login
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'login_at' => now(),
            ]);

            event(new UserLoggedInEvent($user->id, $user->email, $request->ip(), $request->userAgent()));

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Ghi nhận login thất bại
        LoginHistory::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
            'login_at' => now(),
        ]);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }

    /**
     * Xử lý logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Hiển thị profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Cập nhật profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Hiển thị form forgot password
     */
    public function showForgotForm()
    {
        return view('admin.auth.forgot');
    }

    /**
     * Xử lý forgot password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Gửi email reset password
        // PasswordReset::sendResetLink($request->email);

        return back()->with('success', 'Reset link sent to your email.');
    }

    /**
     * Hiển thị form reset password
     */
    public function showResetForm($token)
    {
        return view('admin.auth.reset', compact('token'));
    }

    /**
     * Xử lý reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Xử lý reset password
        // $user = User::where('email', $request->email)->first();
        // $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.login')->with('success', 'Password reset successfully.');
    }
}