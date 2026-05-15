<?php

namespace App\Services\Module01_IAM;

use App\Models\Module01_IAM\Role;
use App\Models\Module01_IAM\Permission;

class RbacService
{
    public function assignRole($user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return ['success' => false, 'message' => 'Role not found'];
        }

        $user->roles()->syncWithoutDetaching([$role->id]);
        return ['success' => true];
    }

    public function removeRole($user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $user->roles()->detach($role->id);
        }

        return ['success' => true];
    }

    public function hasPermission($user, $permission)
    {
        foreach ($user->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function createRole($data)
    {
        return Role::create($data);
    }

    public function assignPermissionToRole($roleName, $permissionName)
    {
        $role = Role::where('name', $roleName)->first();
        $permission = Permission::where('name', $permissionName)->first();

        if ($role && $permission) {
            $role->permissions()->syncWithoutDetaching([$permission->id]);
            return ['success' => true];
        }

        return ['success' => false];
    }
}