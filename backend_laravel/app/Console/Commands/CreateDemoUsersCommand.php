<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module01_IAM\User;
use App\Models\Module01_IAM\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateDemoUsersCommand extends Command
{
    protected $signature = 'demo:create-users';
    protected $description = 'Create demo users for all 10 roles';

    public function handle()
    {
        $this->info('Creating demo users for 10 roles...');

        $demoUsers = [
            ['name' => 'Super Admin Demo', 'email' => 'superadmin@demo.com', 'role' => 'super_admin', 'password' => 'Demo@123456'],
            ['name' => 'Admin Demo', 'email' => 'admin@demo.com', 'role' => 'admin', 'password' => 'Demo@123456'],
            ['name' => 'Security Manager Demo', 'email' => 'securitymanager@demo.com', 'role' => 'security_manager', 'password' => 'Demo@123456'],
            ['name' => 'Compliance Officer Demo', 'email' => 'compliance@demo.com', 'role' => 'compliance_officer', 'password' => 'Demo@123456'],
            ['name' => 'Risk Manager Demo', 'email' => 'riskmanager@demo.com', 'role' => 'risk_manager', 'password' => 'Demo@123456'],
            ['name' => 'Security Analyst Demo', 'email' => 'analyst@demo.com', 'role' => 'security_analyst', 'password' => 'Demo@123456'],
            ['name' => 'Incident Responder Demo', 'email' => 'responder@demo.com', 'role' => 'incident_responder', 'password' => 'Demo@123456'],
            ['name' => 'Vulnerability Scanner Demo', 'email' => 'scanner@demo.com', 'role' => 'vulnerability_scanner', 'password' => 'Demo@123456'],
            ['name' => 'Auditor Demo', 'email' => 'auditor@demo.com', 'role' => 'auditor', 'password' => 'Demo@123456'],
            ['name' => 'Viewer Demo', 'email' => 'viewer@demo.com', 'role' => 'viewer', 'password' => 'Demo@123456'],
        ];

        foreach ($demoUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            $this->info("✓ {$userData['email']} / {$userData['password']}");
        }

        $this->info('✅ All demo users created successfully!');
    }
}