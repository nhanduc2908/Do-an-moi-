<?php

namespace App\Services\Module02_Encryption;

use Illuminate\Support\Facades\Cache;
use App\Models\Module02_Encryption\EncryptedDocument;

class KeyHierarchyService
{
    protected $keyVaultService;

    public function __construct(KeyVaultService $keyVaultService)
    {
        $this->keyVaultService = $keyVaultService;
    }

    public function getKeyEncryptionKey(int $level): string
    {
        $cacheKey = "kek_level_{$level}";
        
        return Cache::remember($cacheKey, 3600, function () use ($level) {
            $masterKey = $this->getMasterKey();
            return hash_hkdf('sha256', $masterKey, 32, "level_{$level}", $this->getSalt());
        });
    }

    private function getMasterKey(): string
    {
        $masterKey = $this->keyVaultService->getMasterKey();
        
        if (!$masterKey) {
            throw new \Exception('Master key not found. Run php artisan security:generate-master-keys');
        }
        
        return $masterKey;
    }

    private function getSalt(): string
    {
        $salt = config('app.key');
        return substr(hash('sha256', $salt), 0, 32);
    }

    public function canAccess(int $userLevel, int $documentLevel): bool
    {
        return $userLevel >= $documentLevel;
    }

    public function getMaxAccessibleLevel(int $userLevel): int
    {
        return $userLevel;
    }

    public function rotateLevelKey(int $level): bool
    {
        $oldKeyCacheKey = "kek_level_{$level}";
        $oldKey = Cache::get($oldKeyCacheKey);
        
        Cache::forget($oldKeyCacheKey);
        
        $newKey = $this->getKeyEncryptionKey($level);
        
        $this->reEncryptDocumentsAtLevel($level, $oldKey, $newKey);
        
        return true;
    }

    private function reEncryptDocumentsAtLevel(int $level, string $oldKey, string $newKey): void
    {
        $documents = EncryptedDocument::where('required_level', $level)->get();
        
        foreach ($documents as $document) {
            $dek = $this->decryptDEK(base64_decode($document->encrypted_key), $oldKey);
            $newEncryptedDek = $this->encryptDEK($dek, $newKey);
            $document->encrypted_key = base64_encode($newEncryptedDek);
            $document->save();
        }
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
}