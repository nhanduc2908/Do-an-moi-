<?php

namespace App\Services\Module02_Encryption;

use App\Models\Module02_Encryption\EncryptedDocument;
use App\Models\Module02_Encryption\DocumentAccessLog;
use App\Models\Module01_IAM\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentEncryptionService
{
    protected $keyHierarchyService;
    protected $algorithm;
    protected $ivLength;

    public function __construct(KeyHierarchyService $keyHierarchyService)
    {
        $this->keyHierarchyService = $keyHierarchyService;
        $this->algorithm = config('document_security.encryption.algorithm', 'aes-256-gcm');
        $this->ivLength = $this->algorithm === 'aes-256-gcm' ? 12 : 16;
    }

    public function encryptDocument(UploadedFile $file, User $uploader, array $metadata): EncryptedDocument
    {
        $dek = random_bytes(32);
        
        $requiredLevel = $metadata['required_level'] ?? 0;
        $kek = $this->keyHierarchyService->getKeyEncryptionKey($requiredLevel);
        
        $content = file_get_contents($file->path());
        list($encryptedContent, $iv, $tag) = $this->encryptContent($content, $dek);
        
        $encryptedDek = $this->encryptDEK($dek, $kek);
        
        $fileHash = hash('sha256', $content);
        
        $storedPath = $this->storeEncryptedFile($encryptedContent, $file->getClientOriginalName());
        
        $document = EncryptedDocument::create([
            'id' => (string) \Str::uuid(),
            'title' => $metadata['title'] ?? $file->getClientOriginalName(),
            'description' => $metadata['description'] ?? null,
            'file_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'encryption_algorithm' => $this->algorithm,
            'encrypted_key' => base64_encode($encryptedDek),
            'iv' => base64_encode($iv),
            'tag' => $tag ? base64_encode($tag) : null,
            'file_hash' => $fileHash,
            'required_level' => $requiredLevel,
            'allowed_roles' => $metadata['allowed_roles'] ?? null,
            'allowed_users' => $metadata['allowed_users'] ?? null,
            'classification' => $metadata['classification'] ?? 'internal',
            'uploaded_by' => $uploader->id,
            'uploaded_at' => now(),
            'expires_at' => $metadata['expires_at'] ?? null,
        ]);
        
        $this->logAccess($document->id, $uploader->id, 'upload', true);
        
        return $document;
    }

    public function decryptDocument(EncryptedDocument $document, User $user, ?string $justification = null): ?string
    {
        if (!$this->canAccessDocument($document, $user)) {
            $this->logAccess($document->id, $user->id, 'decrypt', false, 'Insufficient level');
            throw new \Exception('You do not have permission to access this document (level too low)');
        }
        
        if ($document->requiresMfa() && !$user->mfa_enabled) {
            $this->logAccess($document->id, $user->id, 'decrypt', false, 'MFA required');
            throw new \Exception('MFA is required to access this document');
        }
        
        $userLevel = $this->getUserLevel($user);
        $kek = $this->keyHierarchyService->getKeyEncryptionKey($userLevel);
        
        $dek = $this->decryptDEK(base64_decode($document->encrypted_key), $kek);
        
        $encryptedContent = Storage::get($document->file_path);
        $iv = base64_decode($document->iv);
        $tag = $document->tag ? base64_decode($document->tag) : null;
        
        $decryptedContent = $this->decryptContent($encryptedContent, $dek, $iv, $tag);
        
        if (hash('sha256', $decryptedContent) !== $document->file_hash) {
            throw new \Exception('Document integrity check failed');
        }
        
        $document->increment('access_count');
        $document->last_accessed_at = now();
        $document->save();
        
        $this->logAccess($document->id, $user->id, 'decrypt', true, null, $justification);
        
        return $decryptedContent;
    }

    public function canAccessDocument(EncryptedDocument $document, User $user): bool
    {
        $userLevel = $this->getUserLevel($user);
        $requiredLevel = $document->required_level;
        
        if ($userLevel < $requiredLevel) {
            return false;
        }
        
        if ($document->allowed_roles && !empty($document->allowed_roles)) {
            $userRoles = $user->roles->pluck('name')->toArray();
            if (!array_intersect($userRoles, $document->allowed_roles)) {
                return false;
            }
        }
        
        if ($document->allowed_users && !empty($document->allowed_users)) {
            if (!in_array($user->id, $document->allowed_users)) {
                return false;
            }
        }
        
        if ($document->expires_at && $document->expires_at < now()) {
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

    private function encryptContent(string $plaintext, string $dek): array
    {
        if ($this->algorithm === 'aes-256-gcm') {
            $iv = random_bytes(12);
            $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $dek, OPENSSL_RAW_DATA, $iv, $tag);
            return [$ciphertext, $iv, $tag];
        } else {
            $iv = random_bytes(16);
            $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $dek, OPENSSL_RAW_DATA, $iv);
            return [$ciphertext, $iv, null];
        }
    }

    private function decryptContent(string $ciphertext, string $dek, string $iv, ?string $tag = null): string
    {
        if ($this->algorithm === 'aes-256-gcm') {
            $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $dek, OPENSSL_RAW_DATA, $iv, $tag);
        } else {
            $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $dek, OPENSSL_RAW_DATA, $iv);
        }
        
        if ($plaintext === false) {
            throw new \Exception('Decryption failed');
        }
        
        return $plaintext;
    }

    private function encryptDEK(string $dek, string $kek): string
    {
        $iv = random_bytes(12);
        $ciphertext = openssl_encrypt($dek, 'aes-256-gcm', $kek, OPENSSL_RAW_DATA, $iv, $tag);
        return $iv . $tag . $ciphertext;
    }

    private function decryptDEK(string $encryptedDek, string $kek): string
    {
        $iv = substr($encryptedDek, 0, 12);
        $tag = substr($encryptedDek, 12, 16);
        $ciphertext = substr($encryptedDek, 28);
        
        $dek = openssl_decrypt($ciphertext, 'aes-256-gcm', $kek, OPENSSL_RAW_DATA, $iv, $tag);
        
        if ($dek === false) {
            throw new \Exception('Failed to decrypt DEK - insufficient permissions');
        }
        
        return $dek;
    }

    private function storeEncryptedFile(string $content, string $originalName): string
    {
        $path = 'documents/encrypted/' . date('Y/m/d') . '/' . \Str::uuid() . '.enc';
        
        if (!Storage::disk('local')->exists(dirname($path))) {
            Storage::disk('local')->makeDirectory(dirname($path));
        }
        
        Storage::disk('local')->put($path, $content);
        return $path;
    }

    private function logAccess(string $documentId, string $userId, string $action, bool $granted, ?string $reason = null, ?string $justification = null): void
    {
        DocumentAccessLog::create([
            'document_id' => $documentId,
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'access_granted' => $granted,
            'denial_reason' => $reason,
            'justification' => $justification,
            'accessed_at' => now(),
        ]);
        
        if (!$granted && config('document_security.audit.alert_on_denied_access', true)) {
            Log::warning('Document access denied', [
                'document_id' => $documentId,
                'user_id' => $userId,
                'reason' => $reason,
                'ip' => request()->ip(),
            ]);
        }
    }
}