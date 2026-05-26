<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module16_Compliance\Domain;

class DomainSeeder extends Seeder
{
    public function run()
    {
        $domains = [
            ['name' => 'Access Control', 'code' => 'AC', 'weight' => 15, 'order' => 1],
            ['name' => 'Cryptography', 'code' => 'CR', 'weight' => 10, 'order' => 2],
            ['name' => 'Physical Security', 'code' => 'PS', 'weight' => 10, 'order' => 3],
            ['name' => 'Network Security', 'code' => 'NS', 'weight' => 15, 'order' => 4],
            ['name' => 'Application Security', 'code' => 'AS', 'weight' => 15, 'order' => 5],
            ['name' => 'Incident Response', 'code' => 'IR', 'weight' => 10, 'order' => 6],
            ['name' => 'Compliance', 'code' => 'CM', 'weight' => 15, 'order' => 7],
            ['name' => 'Risk Management', 'code' => 'RM', 'weight' => 10, 'order' => 8],
        ];

        foreach ($domains as $domain) {
            Domain::create($domain);
        }
    }
}