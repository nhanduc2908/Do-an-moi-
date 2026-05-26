<?php

namespace App\Policies;

use App\Models\Module01_IAM\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'auditor']);
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'auditor']);
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function update(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function delete(User $user)
    {
        return $user->hasRole('super_admin');
    }

    public function assignPermissions(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }
}