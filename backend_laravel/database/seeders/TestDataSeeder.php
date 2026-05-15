<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module01_IAM\User;
use App\Models\Module14_IncidentResponse\Incident;
use App\Models\Module21_VulnerabilityManagement\Vulnerability;
use App\Models\Module27_ReportAnalytics\Report;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = User::create([
                'id' => (string) \Str::uuid(),
                'name' => "Test User {$i}",
                'email' => "testuser{$i}@example.com",
                'password' => Hash::make('password'),
                'department' => ['IT', 'Security', 'HR', 'Finance'][array_rand(['IT', 'Security', 'HR', 'Finance'])],
                'status' => 'active',
            ]);
        }
        
        // Create test incidents
        for ($i = 1; $i <= 50; $i++) {
            Incident::factory()->create();
        }
        
        // Create test vulnerabilities
        for ($i = 1; $i <= 100; $i++) {
            Vulnerability::factory()->create();
        }
        
        // Create test reports
        for ($i = 1; $i <= 30; $i++) {
            Report::factory()->create();
        }
    }
}