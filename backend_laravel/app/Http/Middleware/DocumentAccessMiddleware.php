<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Module02_Encryption\EncryptedDocument;

class DocumentAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $documentId = $request->route('document');
        
        if (!$documentId) {
            return $next($request);
        }
        
        $document = EncryptedDocument::find($documentId);
        
        if (!$document) {
            abort(404, 'Document not found');
        }
        
        $user = $request->user();
        
        if (!$user) {
            abort(401, 'Authentication required');
        }
        
        $userLevel = $this->getUserLevel($user);
        $requiredLevel = $document->required_level;
        
        if ($userLevel < $requiredLevel) {
            abort(403, 'You do not have sufficient clearance to access this document');
        }
        
        if ($document->allowed_roles && !empty($document->allowed_roles)) {
            $userRoles = $user->roles->pluck('name')->toArray();
            if (!array_intersect($userRoles, $document->allowed_roles)) {
                abort(403, 'Your role does not have permission to access this document');
            }
        }
        
        if ($document->allowed_users && !empty($document->allowed_users)) {
            if (!in_array($user->id, $document->allowed_users)) {
                abort(403, 'You are not authorized to access this document');
            }
        }
        
        if ($document->expires_at && $document->expires_at < now()) {
            abort(410, 'This document has expired');
        }
        
        return $next($request);
    }

    private function getUserLevel($user): int
    {
        $roleLevels = [
            'super_admin' => 100,
            'admin' => 90,
            'security_manager' => 80,
            'risk_manager' => 75,
            'compliance_officer' => 70,
            'security_analyst' => 60,
            'incident_responder' => 55,
            'vulnerability_scanner' => 45,
            'auditor' => 50,
            'viewer' => 10,
        ];
        
        $role = $user->roles->first();
        return $role ? ($roleLevels[$role->name] ?? 0) : 0;
    }
}