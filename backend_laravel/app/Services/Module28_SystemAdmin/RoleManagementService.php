<?php

namespace App\Services\Module28_SystemAdmin;

use App\Models\Module01_IAM\Role;
use App\Models\Module01_IAM\Permission;

class RoleManagementService
{
    public function createRole($data)
    {
        $role = Role::create([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
            'level' => $data['level'] ?? 1,
            'guard_name' => 'web'
        ]);
        
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        
        return $role;
    }

    public function updateRole($roleId, $data)
    {
        $role = Role::findOrFail($roleId);
        
        $role->update([
            'display_name' => $data['display_name'] ?? $role->display_name,
            'description' => $data['description'] ?? $role->description,
            'level' => $data['level'] ?? $role->level
        ]);
        
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        
        return $role;
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        if ($role->is_system_role) {
            throw new \Exception('Cannot delete system role');
        }
        
        $role->delete();
        
        return true;
    }

    public function assignPermissionToRole($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);
        
        $role->givePermissionTo($permission);
        
        return $role;
    }

    public function removePermissionFromRole($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);
        
        $role->revokePermissionTo($permission);
        
        return $role;
    }

    public function getRoleHierarchy()
    {
        $roles = Role::orderBy('level', 'asc')->get();
        
        $hierarchy = [];
        foreach ($roles as $role) {
            $hierarchy[] = [
                'role' => $role->display_name,
                'level' => $role->level,
                'permissions_count' => $role->permissions()->count(),
                'users_count' => $role->users()->count()
            ];
        }
        
        return $hierarchy;
    }
}