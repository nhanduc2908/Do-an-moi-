<?php

namespace App\Services\Module04_PasswordSecurity;

class PasswordStrengthService
{
    public function checkStrength($password)
    {
        $score = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $score += 10;
        } else {
            $feedback[] = 'Password should be at least 8 characters';
        }

        if (strlen($password) >= 12) $score += 10;
        if (preg_match('/[A-Z]/', $password)) $score += 15;
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 15;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 20;

        if ($this->hasSequentialChars($password)) {
            $score -= 15;
            $feedback[] = 'Contains sequential characters';
        }

        if ($this->hasRepeatedChars($password)) {
            $score -= 10;
            $feedback[] = 'Contains repeated characters';
        }

        $strength = $this->getStrengthLevel($score);

        return [
            'score' => $score,
            'strength' => $strength['level'],
            'color' => $strength['color'],
            'feedback' => $feedback
        ];
    }

    protected function hasSequentialChars($password)
    {
        $sequences = ['123456', 'abcdef', 'qwerty', 'asdfgh', 'zxcvbn'];
        $lowerPass = strtolower($password);
        
        foreach ($sequences as $seq) {
            if (strpos($lowerPass, $seq) !== false) return true;
        }
        return false;
    }

    protected function hasRepeatedChars($password)
    {
        return preg_match('/(.)\1{2,}/', $password);
    }

    protected function getStrengthLevel($score)
    {
        if ($score < 30) return ['level' => 'Very Weak', 'color' => '#dc3545'];
        if ($score < 50) return ['level' => 'Weak', 'color' => '#fd7e14'];
        if ($score < 70) return ['level' => 'Fair', 'color' => '#ffc107'];
        if ($score < 85) return ['level' => 'Strong', 'color' => '#28a745'];
        return ['level' => 'Very Strong', 'color' => '#006400'];
    }
}