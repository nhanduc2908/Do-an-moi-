<?php

namespace App\Services\Module17_PhysicalSecurity;

use App\Models\Module17_PhysicalSecurity\AccessLog;
use App\Models\Module17_PhysicalSecurity\Visitor;

class AccessControlService
{
    public function grantAccess($userId, $doorId, $accessMethod)
    {
        $hasAccess = $this->checkAccessRights($userId, $doorId);
        
        $log = AccessLog::create([
            'user_id' => $userId,
            'door_id' => $doorId,
            'access_type' => 'grant',
            'access_method' => $accessMethod,
            'access_granted' => $hasAccess,
            'reason' => $hasAccess ? 'Authorized access' : 'Access denied',
            'ip_address' => request()->ip(),
            'accessed_at' => now()
        ]);
        
        if ($hasAccess) {
            $this->unlockDoor($doorId, $userId);
        }
        
        return $log;
    }

    public function registerVisitor($visitorData)
    {
        $visitor = Visitor::create([
            'full_name' => $visitorData['name'],
            'identification_number' => $visitorData['id_number'],
            'phone' => $visitorData['phone'],
            'email' => $visitorData['email'],
            'company' => $visitorData['company'],
            'host_employee_id' => $visitorData['host_id'],
            'purpose' => $visitorData['purpose'],
            'check_in_at' => now(),
            'badge_number' => $this->generateBadgeNumber(),
            'status' => 'active'
        ]);
        
        $this->printVisitorBadge($visitor);
        
        return $visitor;
    }

    protected function checkAccessRights($userId, $doorId)
    {
        // Check if user has permission to access this door
        // Based on role, schedule, etc.
        return true;
    }

    protected function unlockDoor($doorId, $userId)
    {
        // Integrate with door control system
    }

    protected function generateBadgeNumber()
    {
        return 'V' . date('Ymd') . rand(1000, 9999);
    }

    protected function printVisitorBadge($visitor)
    {
        // Integrate with badge printer
    }

    public function checkOutVisitor($visitorId)
    {
        $visitor = Visitor::findOrFail($visitorId);
        $visitor->check_out_at = now();
        $visitor->status = 'checked_out';
        $visitor->save();
        
        return $visitor;
    }

    public function getAccessReport($from, $to)
    {
        return AccessLog::whereBetween('accessed_at', [$from, $to])
            ->with('user')
            ->orderBy('accessed_at', 'desc')
            ->get();
    }
}