<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User permissions
            ['name' => 'user.view', 'display_name' => 'View Users', 'module' => 'user'],
            ['name' => 'user.create', 'display_name' => 'Create Users', 'module' => 'user'],
            ['name' => 'user.edit', 'display_name' => 'Edit Users', 'module' => 'user'],
            ['name' => 'user.delete', 'display_name' => 'Delete Users', 'module' => 'user'],
            ['name' => 'user.manage', 'display_name' => 'Manage Users', 'module' => 'user'],
            
            // Role permissions
            ['name' => 'role.view', 'display_name' => 'View Roles', 'module' => 'role'],
            ['name' => 'role.create', 'display_name' => 'Create Roles', 'module' => 'role'],
            ['name' => 'role.edit', 'display_name' => 'Edit Roles', 'module' => 'role'],
            ['name' => 'role.delete', 'display_name' => 'Delete Roles', 'module' => 'role'],
            ['name' => 'role.manage', 'display_name' => 'Manage Roles', 'module' => 'role'],
            
            // Assessment permissions
            ['name' => 'assessment.view', 'display_name' => 'View Assessments', 'module' => 'assessment'],
            ['name' => 'assessment.create', 'display_name' => 'Create Assessments', 'module' => 'assessment'],
            ['name' => 'assessment.edit', 'display_name' => 'Edit Assessments', 'module' => 'assessment'],
            ['name' => 'assessment.delete', 'display_name' => 'Delete Assessments', 'module' => 'assessment'],
            ['name' => 'assessment.review', 'display_name' => 'Review Assessments', 'module' => 'assessment'],
            ['name' => 'assessment.export', 'display_name' => 'Export Assessments', 'module' => 'assessment'],
            
            // Report permissions
            ['name' => 'report.view', 'display_name' => 'View Reports', 'module' => 'report'],
            ['name' => 'report.generate', 'display_name' => 'Generate Reports', 'module' => 'report'],
            ['name' => 'report.export', 'display_name' => 'Export Reports', 'module' => 'report'],
            ['name' => 'report.schedule', 'display_name' => 'Schedule Reports', 'module' => 'report'],
            
            // Incident permissions
            ['name' => 'incident.view', 'display_name' => 'View Incidents', 'module' => 'incident'],
            ['name' => 'incident.create', 'display_name' => 'Create Incidents', 'module' => 'incident'],
            ['name' => 'incident.manage', 'display_name' => 'Manage Incidents', 'module' => 'incident'],
            
            // Compliance permissions
            ['name' => 'compliance.view', 'display_name' => 'View Compliance', 'module' => 'compliance'],
            ['name' => 'compliance.check', 'display_name' => 'Run Compliance Checks', 'module' => 'compliance'],
            
            // System permissions
            ['name' => 'system.config', 'display_name' => 'Configure System', 'module' => 'system'],
            ['name' => 'system.audit', 'display_name' => 'View Audit Logs', 'module' => 'system'],
            ['name' => 'system.backup', 'display_name' => 'Manage Backups', 'module' => 'system'],
            
            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}