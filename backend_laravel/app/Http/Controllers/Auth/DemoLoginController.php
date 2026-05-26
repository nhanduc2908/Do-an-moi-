<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Module01_IAM\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DemoLoginController extends Controller
{
    /**
     * Hiển thị danh sách tài khoản demo
     */
    public function showDemoAccounts()
    {
        $demoAccounts = [
            ['role' => 'Super Administrator', 'email' => 'superadmin@demo.com', 'password' => 'Demo@123456', 'level' => 100, 'color' => '#dc3545', 'icon' => '👑'],
            ['role' => 'Administrator', 'email' => 'admin@demo.com', 'password' => 'Demo@123456', 'level' => 90, 'color' => '#e74c3c', 'icon' => '⚙️'],
            ['role' => 'Security Manager', 'email' => 'securitymanager@demo.com', 'password' => 'Demo@123456', 'level' => 80, 'color' => '#e67e22', 'icon' => '🛡️'],
            ['role' => 'Compliance Officer', 'email' => 'compliance@demo.com', 'password' => 'Demo@123456', 'level' => 70, 'color' => '#2ecc71', 'icon' => '📋'],
            ['role' => 'Risk Manager', 'email' => 'riskmanager@demo.com', 'password' => 'Demo@123456', 'level' => 75, 'color' => '#f39c12', 'icon' => '📊'],
            ['role' => 'Security Analyst', 'email' => 'analyst@demo.com', 'password' => 'Demo@123456', 'level' => 60, 'color' => '#3498db', 'icon' => '🔍'],
            ['role' => 'Incident Responder', 'email' => 'responder@demo.com', 'password' => 'Demo@123456', 'level' => 55, 'color' => '#e84393', 'icon' => '🚨'],
            ['role' => 'Vulnerability Scanner', 'email' => 'scanner@demo.com', 'password' => 'Demo@123456', 'level' => 45, 'color' => '#1abc9c', 'icon' => '🔬'],
            ['role' => 'Auditor', 'email' => 'auditor@demo.com', 'password' => 'Demo@123456', 'level' => 30, 'color' => '#95a5a6', 'icon' => '📜'],
            ['role' => 'Viewer', 'email' => 'viewer@demo.com', 'password' => 'Demo@123456', 'level' => 10, 'color' => '#7f8c8d', 'icon' => '👁️'],
        ];

        return view('auth.demo-login', compact('demoAccounts'));
    }

    /**
     * Đăng nhập bằng tài khoản demo
     */
    public function demoLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Demo account not found. Please run: php artisan demo:create-users']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        Auth::login($user, $request->has('remember'));

        $user->update(['last_login_at' => now()]);

        $role = $user->roles->first();
        $dashboardRoutes = [
            'super_admin' => 'super-admin.dashboard',
            'admin' => 'admin.dashboard',
            'security_manager' => 'security-manager.dashboard',
            'compliance_officer' => 'compliance-officer.dashboard',
            'risk_manager' => 'risk-manager.dashboard',
            'security_analyst' => 'security-analyst.dashboard',
            'incident_responder' => 'incident-responder.dashboard',
            'vulnerability_scanner' => 'vulnerability-scanner.dashboard',
            'auditor' => 'auditor.dashboard',
            'viewer' => 'viewer.dashboard',
        ];

        $route = $dashboardRoutes[$role->name] ?? 'viewer.dashboard';
        
        return redirect()->route($route)->with('success', 'Welcome ' . $user->name . '!');
    }

    /**
     * Quick login - Đăng nhập nhanh bằng 1 click
     */
    public function quickLogin($role)
    {
        $roleEmails = [
            'super_admin' => 'superadmin@demo.com',
            'admin' => 'admin@demo.com',
            'security_manager' => 'securitymanager@demo.com',
            'compliance_officer' => 'compliance@demo.com',
            'risk_manager' => 'riskmanager@demo.com',
            'security_analyst' => 'analyst@demo.com',
            'incident_responder' => 'responder@demo.com',
            'vulnerability_scanner' => 'scanner@demo.com',
            'auditor' => 'auditor@demo.com',
            'viewer' => 'viewer@demo.com',
        ];

        if (!isset($roleEmails[$role])) {
            abort(404, 'Role not found');
        }

        $user = User::where('email', $roleEmails[$role])->first();

        if (!$user) {
            return redirect()->route('demo.accounts')->with('error', 'Demo account not found. Please run: php artisan demo:create-users');
        }

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        $dashboardRoutes = [
            'super_admin' => 'super-admin.dashboard',
            'admin' => 'admin.dashboard',
            'security_manager' => 'security-manager.dashboard',
            'compliance_officer' => 'compliance-officer.dashboard',
            'risk_manager' => 'risk-manager.dashboard',
            'security_analyst' => 'security-analyst.dashboard',
            'incident_responder' => 'incident-responder.dashboard',
            'vulnerability_scanner' => 'vulnerability-scanner.dashboard',
            'auditor' => 'auditor.dashboard',
            'viewer' => 'viewer.dashboard',
        ];

        return redirect()->route($dashboardRoutes[$role]);
    }
}