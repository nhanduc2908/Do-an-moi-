<?php

namespace App\Services\Module08_DatabaseSecurity;

class DataMaskingService
{
    public function maskEmail($email)
    {
        if (!$email) return null;
        $parts = explode('@', $email);
        $name = $parts[0];
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(3, strlen($name) - 3)) . substr($name, -1);
        return $maskedName . '@' . $parts[1];
    }

    public function maskPhone($phone)
    {
        if (!$phone) return null;
        return substr($phone, 0, 3) . '****' . substr($phone, -3);
    }

    public function maskCreditCard($cardNumber)
    {
        if (!$cardNumber) return null;
        return '****-****-****-' . substr($cardNumber, -4);
    }

    public function maskSsn($ssn)
    {
        if (!$ssn) return null;
        return '***-**-' . substr($ssn, -4);
    }

    public function maskText($text, $visibleStart = 2, $visibleEnd = 2)
    {
        if (!$text) return null;
        $length = strlen($text);
        if ($length <= $visibleStart + $visibleEnd) {
            return str_repeat('*', $length);
        }
        
        $start = substr($text, 0, $visibleStart);
        $middle = str_repeat('*', $length - $visibleStart - $visibleEnd);
        $end = substr($text, -$visibleEnd);
        
        return $start . $middle . $end;
    }

    public function applyMaskingLevel($data, $level = 'medium')
    {
        $levels = [
            'low' => ['email' => 'partial', 'phone' => 'partial'],
            'medium' => ['email' => 'full', 'phone' => 'full', 'ssn' => 'partial'],
            'high' => ['email' => 'full', 'phone' => 'full', 'ssn' => 'full', 'text' => 'full']
        ];
        
        $result = [];
        foreach ($data as $key => $value) {
            if (isset($levels[$level][$key])) {
                $method = 'mask' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $result[$key] = $this->$method($value);
                }
            } else {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }
}