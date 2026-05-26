<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\Role;
use App\Models\Module16_Compliance\Domain;
use Illuminate\Support\Facades\DB;

class RoleDomainVersionSeeder extends Seeder
{
    public function run()
    {
        $roles = Role::all();
        $domains = Domain::all();
        
        $permissionsByRole = [
            'super_admin' => ['*'],
            'admin' => ['view', 'create', 'edit', 'delete', 'manage'],
            'security_manager' => ['view', 'create', 'edit', 'review', 'export'],
            'security_analyst' => ['view', 'create', 'edit'],
            'compliance_officer' => ['view', 'check', 'export'],
            'auditor' => ['view'],
            'viewer' => ['view'],
        ];
        
        foreach ($roles as $role) {
            $roleName = $role->name;
            $permissions = $permissionsByRole[$roleName] ?? ['view'];
            
            foreach ($domains as $domain) {
                DB::table('role_domain_version')->insert([
                    'role_id' => $role->id,
                    'domain_id' => $domain->id,
                    'version' => '1.0',
                    'permissions' => json_encode($permissions),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}