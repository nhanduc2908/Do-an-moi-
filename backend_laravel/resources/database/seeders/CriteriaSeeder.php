<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module16_Compliance\Domain;
use App\Models\Module16_Compliance\Criteria;
use Illuminate\Support\Str;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        $domains = Domain::all();
        
        $criteriaList = [
            'Access Control' => [
                ['code' => 'AC-01', 'name' => 'User Registration Process', 'description' => 'Formal user registration and de-provisioning process shall be implemented.', 'weight' => 3],
                ['code' => 'AC-02', 'name' => 'Unique Identification', 'description' => 'All users shall have a unique identifier.', 'weight' => 5],
                ['code' => 'AC-03', 'name' => 'Password Management', 'description' => 'Password policies shall be enforced.', 'weight' => 4],
                ['code' => 'AC-04', 'name' => 'Access Review', 'description' => 'User access shall be reviewed periodically.', 'weight' => 4],
                ['code' => 'AC-05', 'name' => 'Least Privilege', 'description' => 'Users shall have minimum required access.', 'weight' => 5],
            ],
            'Cryptography' => [
                ['code' => 'CR-01', 'name' => 'Data at Rest Encryption', 'description' => 'Sensitive data at rest shall be encrypted.', 'weight' => 5],
                ['code' => 'CR-02', 'name' => 'Data in Transit Encryption', 'description' => 'Data in transit shall be encrypted using TLS.', 'weight' => 5],
                ['code' => 'CR-03', 'name' => 'Key Management', 'description' => 'Cryptographic keys shall be properly managed.', 'weight' => 4],
            ],
            'Network Security' => [
                ['code' => 'NS-01', 'name' => 'Firewall Configuration', 'description' => 'Firewalls shall be properly configured.', 'weight' => 4],
                ['code' => 'NS-02', 'name' => 'Network Segmentation', 'description' => 'Network shall be properly segmented.', 'weight' => 3],
                ['code' => 'NS-03', 'name' => 'Intrusion Detection', 'description' => 'IDS/IPS shall be implemented.', 'weight' => 4],
            ],
        ];

        foreach ($criteriaList as $domainName => $criteria) {
            $domain = $domains->where('name', $domainName)->first();
            if ($domain) {
                foreach ($criteria as $item) {
                    Criteria::create([
                        'id' => (string) Str::uuid(),
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'domain_id' => $domain->id,
                        'weight' => $item['weight'],
                        'max_score' => 5,
                        'min_score' => 0,
                        'passing_score' => 3,
                        'evidence_required' => true,
                        'status' => 'active',
                    ]);
                }
            }
        }
    }
}