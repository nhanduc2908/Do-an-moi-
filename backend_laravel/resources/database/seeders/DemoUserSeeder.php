<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\User;
use App\Models\Module01_IAM\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        $demoUsers = [
            [
                'name' => 'Super Admin Demo',
                'email' => 'superadmin@demo.com',
                'role' => 'super_admin',
                'password' => 'Demo@123456',
                'department' => 'IT',
                'position' => 'System Administrator',
            ],
            [
                'name' => 'Admin Demo',
                'email' => 'admin@demo.com',
                'role' => 'admin',
                'password' => 'Demo@123456',
                'department' => 'IT',
                'position' => 'Administrator',
            ],
            [
                'name' => 'Security Manager Demo',
                'email' => 'securitymanager@demo.com',
                'role' => 'security_manager',
                'password' => 'Demo@123456',
                'department' => 'Security',
                'position' => 'Security Manager',
            ],
            [
                'name' => 'Compliance Officer Demo',
                'email' => 'compliance@demo.com',
                'role' => 'compliance_officer',
                'password' => 'Demo@123456',
                'department' => 'Compliance',
                'position' => 'Compliance Officer',
            ],
            [
                'name' => 'Risk Manager Demo',
                'email' => 'riskmanager@demo.com',
                'role' => 'risk_manager',
                'password' => 'Demo@123456',
                'department' => 'Risk',
                'position' => 'Risk Manager',
            ],
            [
                'name' => 'Security Analyst Demo',
                'email' => 'analyst@demo.com',
                'role' => 'security_analyst',
                'password' => 'Demo@123456',
                'department' => 'Security',
                'position' => 'Security Analyst',
            ],
            [
                'name' => 'Incident Responder Demo',
                'email' => 'responder@demo.com',
                'role' => 'incident_responder',
                'password' => 'Demo@123456',
                'department' => 'Security',
                'position' => 'Incident Responder',
            ],
            [
                'name' => 'Vulnerability Scanner Demo',
                'email' => 'scanner@demo.com',
                'role' => 'vulnerability_scanner',
                'password' => 'Demo@123456',
                'department' => 'Security',
                'position' => 'Vulnerability Scanner',
            ],
            [
                'name' => 'Auditor Demo',
                'email' => 'auditor@demo.com',
                'role' => 'auditor',
                'password' => 'Demo@123456',
                'department' => 'Audit',
                'position' => 'Auditor',
            ],
            [
                'name' => 'Viewer Demo',
                'email' => 'viewer@demo.com',
                'role' => 'viewer',
                'password' => 'Demo@123456',
                'department' => 'Guest',
                'position' => 'Viewer',
            ],
        ];

        foreach ($demoUsers as $userData) {
            // Check if user already exists
            $user = User::where('email', $userData['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'department' => $userData['department'],
                    'position' => $userData['position'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // Assign role
            $role = Role::where('name', $userData['role'])->first();
            if ($role && !$user->hasRole($userData['role'])) {
                $user->roles()->attach($role->id);
            }

            $this->command->info("✓ Demo user created: {$userData['email']} / {$userData['password']}");
        }
    }
}