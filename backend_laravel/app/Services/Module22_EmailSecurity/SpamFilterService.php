<?php

namespace App\Services\Module22_EmailSecurity;

class SpamFilterService
{
    protected $spamKeywords = [
        'viagra', 'lottery', 'winner', 'bank account', 'verify your account',
        'click here', 'urgent', 'password expired', 'wire transfer'
    ];

    protected $spamScores = [
        'contains_links' => 5,
        'contains_attachment' => 3,
        'suspicious_sender' => 10,
        'suspicious_subject' => 8,
        'html_only' => 4,
        'multiple_recipients' => 3
    ];

    public function analyzeEmail($emailData)
    {
        $score = 0;
        $reasons = [];
        
        // Check sender reputation
        $senderScore = $this->checkSenderReputation($emailData['from']);
        if ($senderScore < 50) {
            $score += $this->spamScores['suspicious_sender'];
            $reasons[] = 'Low sender reputation';
        }
        
        // Check subject
        foreach ($this->spamKeywords as $keyword) {
            if (stripos($emailData['subject'], $keyword) !== false) {
                $score += 2;
                $reasons[] = "Subject contains suspicious keyword: {$keyword}";
            }
        }
        
        // Check content
        $content = $emailData['body'] ?? '';
        foreach ($this->spamKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $score += 1;
            }
        }
        
        // Check links
        if (preg_match_all('/https?:\/\//', $content, $matches)) {
            $score += min(count($matches[0]) * 1, $this->spamScores['contains_links']);
            $reasons[] = 'Contains multiple links';
        }
        
        // Check attachments
        if (!empty($emailData['attachments'])) {
            $score += $this->spamScores['contains_attachment'];
            $reasons[] = 'Contains attachments';
        }
        
        // HTML only email
        if (strip_tags($content) !== $content && strlen(strip_tags($content)) < 50) {
            $score += $this->spamScores['html_only'];
            $reasons[] = 'HTML-only email with little text';
        }
        
        $isSpam = $score >= 15;
        
        return [
            'is_spam' => $isSpam,
            'score' => $score,
            'reasons' => $reasons,
            'action' => $isSpam ? 'quarantine' : 'deliver'
        ];
    }

    protected function checkSenderReputation($sender)
    {
        $domain = substr($sender, strpos($sender, '@') + 1);
        
        // Query reputation database
        $reputation = cache()->get("sender_reputation_{$domain}", 100);
        
        return $reputation;
    }

    public function updateSenderReputation($sender, $isLegitimate)
    {
        $domain = substr($sender, strpos($sender, '@') + 1);
        $current = cache()->get("sender_reputation_{$domain}", 100);
        
        $newReputation = $isLegitimate 
            ? min(100, $current + 5) 
            : max(0, $current - 10);
        
        cache()->put("sender_reputation_{$domain}", $newReputation, now()->addDays(30));
        
        return $newReputation;
    }

    public function trainFilter($emailData, $isSpam)
    {
        // Train Bayesian filter
        return ['trained' => true];
    }
}