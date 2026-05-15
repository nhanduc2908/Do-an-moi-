<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Module01_IAM\Role;
use App\Models\Module01_IAM\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_role()
    {
        $this->actingAsAdmin();

        $response = $this->post('/api/admin/roles', [
            'name' => 'test_role',
            'display_name' => 'Test Role',
            'level' => 50
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'test_role']);
    }

    public function test_admin_can_assign_permissions_to_role()
    {
        $this->actingAsAdmin();
        
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();

        $response = $this->put("/api/admin/roles/{$role->id}/permissions", [
            'permissions' => [$permission->name]
        ]);

        $response->assertStatus(200);
        $this->assertTrue($role->hasPermissionTo($permission->name));
    }

    public function test_regular_user_cannot_create_role()
    {
        $this->actingAsUser();

        $response = $this->post('/api/admin/roles', [
            'name' => 'unauthorized_role',
            'display_name' => 'Unauthorized'
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_role()
    {
        $this->actingAsAdmin();
        
        $role = Role::factory()->create(['is_system_role' => false]);

        $response = $this->delete("/api/admin/roles/{$role->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}