<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'level' => 100, 'is_system_role' => true],
            ['name' => 'admin', 'display_name' => 'Administrator', 'level' => 80, 'is_system_role' => true],
            ['name' => 'security_manager', 'display_name' => 'Security Manager', 'level' => 70, 'is_system_role' => true],
            ['name' => 'security_analyst', 'display_name' => 'Security Analyst', 'level' => 50, 'is_system_role' => true],
            ['name' => 'compliance_officer', 'display_name' => 'Compliance Officer', 'level' => 60, 'is_system_role' => true],
            ['name' => 'auditor', 'display_name' => 'Auditor', 'level' => 40, 'is_system_role' => true],
            ['name' => 'viewer', 'display_name' => 'Viewer', 'level' => 10, 'is_system_role' => true],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}