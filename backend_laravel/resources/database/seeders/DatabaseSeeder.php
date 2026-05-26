DatabaseSeeder.php<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            DomainSeeder::class,
            CriteriaSeeder::class,
            RoleDomainVersionSeeder::class,
            AdminUserSeeder::class,
            ComplianceStandardsSeeder::class,
            DemoUserSeeder::class,  // <--- THÊM DÒNG NÀY
        ]);
    }
}