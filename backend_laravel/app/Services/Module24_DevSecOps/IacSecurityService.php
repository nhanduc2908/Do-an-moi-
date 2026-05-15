<?php

namespace App\Services\Module24_DevSecOps;

use App\Models\Module24_DevSecOps\IacTemplate;

class IacSecurityService
{
    protected $terraformRules = [
        'aws_s3_bucket' => [
            'acl' => ['value' => 'private', 'severity' => 'critical'],
            'versioning' => ['value' => true, 'severity' => 'high'],
            'encryption' => ['value' => true, 'severity' => 'high']
        ],
        'aws_security_group' => [
            'ingress' => [
                'cidr_blocks' => ['pattern' => '/0.0.0.0\/0/', 'severity' => 'critical']
            ]
        ],
        'aws_iam_policy' => [
            'policy' => ['pattern' => '/\*/', 'severity' => 'critical']
        ]
    ];

    public function scanTerraform($content, $templateName)
    {
        $misconfigurations = [];
        
        foreach ($this->terraformRules as $resource => $rules) {
            if (strpos($content, $resource) !== false) {
                foreach ($rules as $attribute => $rule) {
                    if ($this->checkMisconfiguration($content, $resource, $attribute, $rule)) {
                        $misconfigurations[] = [
                            'resource' => $resource,
                            'attribute' => $attribute,
                            'severity' => $rule['severity'],
                            'recommendation' => $this->getRecommendation($resource, $attribute)
                        ];
                    }
                }
            }
        }
        
        $template = IacTemplate::updateOrCreate(
            ['template_name' => $templateName],
            [
                'template_type' => 'terraform',
                'content' => $content,
                'misconfigurations' => $misconfigurations,
                'is_secure' => empty($misconfigurations),
                'last_scanned_at' => now()
            ]
        );
        
        return $template;
    }

    protected function checkMisconfiguration($content, $resource, $attribute, $rule)
    {
        $pattern = "/{$resource}\s*\{[^}]*{$attribute}\s*=\s*([^\n]+)/s";
        preg_match($pattern, $content, $matches);
        
        if (empty($matches)) return false;
        
        $value = trim($matches[1]);
        
        if (isset($rule['value'])) {
            return $value !== $rule['value'];
        }
        
        if (isset($rule['pattern'])) {
            return preg_match($rule['pattern'], $value) === 1;
        }
        
        return false;
    }

    protected function getRecommendation($resource, $attribute)
    {
        $recommendations = [
            'aws_s3_bucket' => [
                'acl' => 'Set ACL to private',
                'versioning' => 'Enable bucket versioning',
                'encryption' => 'Enable bucket encryption'
            ],
            'aws_security_group' => [
                'ingress' => 'Restrict ingress to specific IP ranges'
            ],
            'aws_iam_policy' => [
                'policy' => 'Avoid using wildcard (*) in IAM policies'
            ]
        ];
        
        return $recommendations[$resource][$attribute] ?? 'Review configuration';
    }

    public function scanKubernetes($manifest)
    {
        $issues = [];
        
        // Check for privileged containers
        if (strpos($manifest, 'privileged: true') !== false) {
            $issues[] = [
                'type' => 'privileged_container',
                'severity' => 'critical',
                'recommendation' => 'Avoid privileged containers'
            ];
        }
        
        // Check for hostNetwork
        if (strpos($manifest, 'hostNetwork: true') !== false) {
            $issues[] = [
                'type' => 'host_network',
                'severity' => 'high',
                'recommendation' => 'Avoid hostNetwork'
            ];
        }
        
        // Check for runAsNonRoot
        if (strpos($manifest, 'runAsNonRoot: false') !== false) {
            $issues[] = [
                'type' => 'run_as_root',
                'severity' => 'medium',
                'recommendation' => 'Run containers as non-root user'
            ];
        }
        
        return $issues;
    }

    public function fixMisconfiguration($templateId, $autoFix = true)
    {
        $template = IacTemplate::findOrFail($templateId);
        
        if (!$autoFix) {
            return ['fixed' => false, 'message' => 'Manual fix required'];
        }
        
        $fixedContent = $template->content;
        
        foreach ($template->misconfigurations as $misconfig) {
            $fixedContent = $this->applyFix($fixedContent, $misconfig);
        }
        
        $template->content = $fixedContent;
        $template->misconfigurations = [];
        $template->is_secure = true;
        $template->save();
        
        return ['fixed' => true, 'fixed_content' => $fixedContent];
    }

    protected function applyFix($content, $misconfig)
    {
        $fixes = [
            'acl' => ['search' => 'acl = "public-read"', 'replace' => 'acl = "private"'],
            'versioning' => ['search' => 'versioning = false', 'replace' => 'versioning = true'],
            'encryption' => ['search' => 'encryption = false', 'replace' => 'encryption = true']
        ];
        
        if (isset($fixes[$misconfig['attribute']])) {
            $fix = $fixes[$misconfig['attribute']];
            $content = str_replace($fix['search'], $fix['replace'], $content);
        }
        
        return $content;
    }
}