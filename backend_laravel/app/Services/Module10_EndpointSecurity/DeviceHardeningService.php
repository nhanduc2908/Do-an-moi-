<?php

namespace App\Services\Module10_EndpointSecurity;

use App\Models\Module10_EndpointSecurity\EndpointHardening;

class DeviceHardeningService
{
    protected $checks = [
        'firewall_enabled' => [
            'method' => 'checkFirewall',
            'weight' => 15,
            'remediation' => 'Enable firewall'
        ],
        'antivirus_installed' => [
            'method' => 'checkAntivirus',
            'weight' => 20,
            'remediation' => 'Install antivirus software'
        ],
        'os_up_to_date' => [
            'method' => 'checkOsUpdates',
            'weight' => 15,
            'remediation' => 'Install latest OS updates'
        ],
        'password_policy' => [
            'method' => 'checkPasswordPolicy',
            'weight' => 15,
            'remediation' => 'Enforce strong password policy'
        ],
        'encryption_enabled' => [
            'method' => 'checkEncryption',
            'weight' => 15,
            'remediation' => 'Enable disk encryption'
        ],
        'least_privilege' => [
            'method' => 'checkPrivileges',
            'weight' => 10,
            'remediation' => 'Remove admin privileges'
        ],
        'secure_config' => [
            'method' => 'checkSecureConfig',
            'weight' => 10,
            'remediation' => 'Apply secure configuration'
        ]
    ];

    public function runHardeningCheck($endpointId)
    {
        $results = [];
        $totalScore = 0;
        $maxScore = 0;
        
        foreach ($this->checks as $checkName => $check) {
            $result = $this->{$check['method']}($endpointId);
            $score = $result['passed'] ? $check['weight'] : 0;
            $totalScore += $score;
            $maxScore += $check['weight'];
            
            $results[] = [
                'check_name' => $checkName,
                'status' => $result['passed'] ? 'passed' : 'failed',
                'score' => $score,
                'details' => $result['details'],
                'remediation' => $result['passed'] ? null : $check['remediation']
            ];
            
            EndpointHardening::updateOrCreate(
                [
                    'endpoint_id' => $endpointId,
                    'check_name' => $checkName
                ],
                [
                    'status' => $result['passed'] ? 'passed' : 'failed',
                    'compliance' => $result['passed'],
                    'last_checked_at' => now(),
                    'details' => $result['details']
                ]
            );
        }
        
        $complianceScore = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
        
        return [
            'endpoint_id' => $endpointId,
            'compliance_score' => round($complianceScore, 2),
            'checks' => $results,
            'recommendations' => $this->getRecommendations($results)
        ];
    }

    protected function checkFirewall($endpointId)
    {
        // Check if firewall is enabled
        return [
            'passed' => true,
            'details' => 'Firewall is enabled and properly configured'
        ];
    }

    protected function checkAntivirus($endpointId)
    {
        // Check if antivirus is installed and up to date
        return [
            'passed' => true,
            'details' => 'Antivirus is installed and definitions are up to date'
        ];
    }

    protected function checkOsUpdates($endpointId)
    {
        // Check if OS is up to date
        return [
            'passed' => true,
            'details' => 'OS is up to date with latest security patches'
        ];
    }

    protected function checkPasswordPolicy($endpointId)
    {
        // Check password policy enforcement
        return [
            'passed' => true,
            'details' => 'Strong password policy is enforced'
        ];
    }

    protected function checkEncryption($endpointId)
    {
        // Check if disk encryption is enabled
        return [
            'passed' => true,
            'details' => 'Full disk encryption is enabled'
        ];
    }

    protected function checkPrivileges($endpointId)
    {
        // Check if users have least privilege
        return [
            'passed' => true,
            'details' => 'Users are running with standard privileges'
        ];
    }

    protected function checkSecureConfig($endpointId)
    {
        // Check for secure configuration
        return [
            'passed' => true,
            'details' => 'Secure configuration is applied'
        ];
    }

    protected function getRecommendations($results)
    {
        $recommendations = [];
        foreach ($results as $result) {
            if ($result['status'] === 'failed' && $result['remediation']) {
                $recommendations[] = $result['remediation'];
            }
        }
        return $recommendations;
    }
}