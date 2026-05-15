<?php

namespace App\Services\Module26_AIEngine;

use App\Models\Module26_AIEngine\AICriteriaSuggestion;

class AICriteriaGeneratorService
{
    public function generateCriteria($domain, $requirements)
    {
        $suggestions = [];
        
        // Parse requirements
        $keywords = $this->extractKeywords($requirements);
        
        // Generate criteria based on domain and keywords
        $criteriaTemplates = $this->getCriteriaTemplates($domain);
        
        foreach ($criteriaTemplates as $template) {
            $criteria = $this->customizeCriteria($template, $keywords);
            $suggestions[] = $criteria;
        }
        
        $suggestion = AICriteriaSuggestion::create([
            'domain' => $domain,
            'requirements' => $requirements,
            'suggested_criteria' => $suggestions,
            'confidence_score' => $this->calculateConfidence($suggestions),
            'generated_by' => 'ai'
        ]);
        
        return $suggestion;
    }

    protected function extractKeywords($text)
    {
        $keywords = [];
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for'];
        
        $words = str_word_count(strtolower($text), 1);
        
        foreach ($words as $word) {
            if (!in_array($word, $stopWords) && strlen($word) > 3) {
                $keywords[$word] = ($keywords[$word] ?? 0) + 1;
            }
        }
        
        arsort($keywords);
        
        return array_keys(array_slice($keywords, 0, 10));
    }

    protected function getCriteriaTemplates($domain)
    {
        $templates = [
            'security' => [
                'Access Control' => 'Ensure only authorized users can access {asset}',
                'Data Protection' => 'Encrypt {data_type} data at rest and in transit',
                'Audit Logging' => 'Maintain audit logs for {action} activities'
            ],
            'compliance' => [
                'Policies' => 'Document and approve {policy_type} policies',
                'Training' => 'Provide security awareness training every {frequency}',
                'Reviews' => 'Conduct compliance reviews {periodically}'
            ]
        ];
        
        return $templates[$domain] ?? $templates['security'];
    }

    protected function customizeCriteria($template, $keywords)
    {
        $criteria = $template;
        
        // Replace placeholders with relevant keywords
        foreach ($keywords as $keyword) {
            if (strpos($criteria, '{') !== false) {
                $criteria = preg_replace('/\{([^}]+)\}/', $keyword, $criteria, 1);
            }
        }
        
        // Replace any remaining placeholders
        $criteria = preg_replace('/\{([^}]+)\}/', 'relevant', $criteria);
        
        return $criteria;
    }

    protected function calculateConfidence($suggestions)
    {
        // Calculate confidence based on number of suggestions and keywords
        $confidence = min(count($suggestions) / 10, 0.95);
        
        return $confidence;
    }

    public function applySuggestion($suggestionId)
    {
        $suggestion = AICriteriaSuggestion::findOrFail($suggestionId);
        $suggestion->is_applied = true;
        $suggestion->applied_at = now();
        $suggestion->save();
        
        return $suggestion;
    }
}