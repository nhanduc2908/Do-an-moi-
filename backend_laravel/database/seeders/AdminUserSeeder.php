<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\User;
use App\Models\Module01_IAM\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'id' => (string) \Str::uuid(),
            'name' => 'System Administrator',
            'email' => 'admin@securityplatform.com',
            'password' => Hash::make('Admin@123456'),
            'department' => 'IT',
            'position' => 'System Admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $admin->roles()->attach($superAdminRole->id);
        }
    }
}