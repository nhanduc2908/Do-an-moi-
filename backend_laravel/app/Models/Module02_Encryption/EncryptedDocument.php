<?php

namespace App\Models\Module02_Encryption;

use App\Models\Module01_IAM\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncryptedDocument extends Model
{
    use SoftDeletes;

    protected $table = 'encrypted_documents';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'title', 'description', 'file_path', 'original_name',
        'mime_type', 'file_size', 'encryption_algorithm', 'encrypted_key',
        'iv', 'tag', 'file_hash', 'required_level', 'allowed_roles',
        'allowed_users', 'classification', 'uploaded_by', 'uploaded_at',
        'expires_at', 'last_accessed_at', 'access_count', 'is_deleted'
    ];

    protected $casts = [
        'allowed_roles' => 'array',
        'allowed_users' => 'array',
        'uploaded_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function accessLogs()
    {
        return $this->hasMany(DocumentAccessLog::class, 'document_id');
    }

    public function canBeAccessedBy(User $user): bool
    {
        $userLevel = $this->getUserLevel($user);
        
        if ($userLevel < $this->required_level) {
            return false;
        }
        
        if ($this->allowed_roles && !empty($this->allowed_roles)) {
            $userRoles = $user->roles->pluck('name')->toArray();
            if (!array_intersect($userRoles, $this->allowed_roles)) {
                return false;
            }
        }
        
        if ($this->allowed_users && !empty($this->allowed_users)) {
            if (!in_array($user->id, $this->allowed_users)) {
                return false;
            }
        }
        
        if ($this->expires_at && $this->expires_at < now()) {
            return false;
        }
        
        return true;
    }

    private function getUserLevel(User $user): int
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

    public function getClassificationLevel(): int
    {
        $classifications = config('document_security.classifications', []);
        return $classifications[$this->classification]['level'] ?? 0;
    }

    public function requiresMfa(): bool
    {
        $classifications = config('document_security.classifications', []);
        return $classifications[$this->classification]['requires_mfa'] ?? false;
    }

    public function getClassificationColor(): string
    {
        $classifications = config('document_security.classifications', []);
        return $classifications[$this->classification]['color'] ?? '#6c757d';
    }
}