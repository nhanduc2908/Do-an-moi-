<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $adminUser;
    protected $securityManager;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->adminUser = \App\Models\Module01_IAM\User::factory()->create([
            'email' => 'admin@test.com',
            'status' => 'active'
        ]);
        
        $this->securityManager = \App\Models\Module01_IAM\User::factory()->create([
            'email' => 'manager@test.com',
            'status' => 'active'
        ]);
        
        $this->regularUser = \App\Models\Module01_IAM\User::factory()->create([
            'email' => 'user@test.com',
            'status' => 'active'
        ]);
        
        // Assign roles
        $adminRole = \App\Models\Module01_IAM\Role::where('name', 'super_admin')->first();
        $managerRole = \App\Models\Module01_IAM\Role::where('name', 'security_manager')->first();
        $userRole = \App\Models\Module01_IAM\Role::where('name', 'viewer')->first();
        
        if ($adminRole) $this->adminUser->roles()->attach($adminRole->id);
        if ($managerRole) $this->securityManager->roles()->attach($managerRole->id);
        if ($userRole) $this->regularUser->roles()->attach($userRole->id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function actingAsAdmin()
    {
        return $this->actingAs($this->adminUser);
    }

    protected function actingAsManager()
    {
        return $this->actingAs($this->securityManager);
    }

    protected function actingAsUser()
    {
        return $this->actingAs($this->regularUser);
    }
}