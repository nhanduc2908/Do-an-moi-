<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Module01_IAM\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'wrong@test.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get('/api/user/profile');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_protected_route()
    {
        $response = $this->get('/api/user/profile');
        $response->assertStatus(401);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        $response = $this->actingAs($user)
                         ->post('/api/user/change-password', [
                             'current_password' => 'oldpassword',
                             'new_password' => 'newpassword123',
                             'new_password_confirmation' => 'newpassword123'
                         ]);

        $response->assertStatus(200);
    }
}