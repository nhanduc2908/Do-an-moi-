<?php

namespace App\Services\Module02_Encryption;

use App\Models\Module02_Encryption\EncryptionKey;
use App\Models\Module02_Encryption\KeyLog;
use Illuminate\Support\Facades\Crypt;

class KeyVaultService
{
    public function storeKey($keyData)
    {
        $encryptedKey = Crypt::encryptString($keyData['key_material']);
        
        return EncryptionKey::create([
            'key_id' => $keyData['key_id'] ?? $this->generateKeyId(),
            'type' => $keyData['type'],
            'size' => $keyData['size'],
            'purpose' => $keyData['purpose'],
            'encrypted_key' => $encryptedKey,
            'metadata' => $keyData['metadata'] ?? [],
            'expires_at' => $keyData['expires_at'] ?? null,
            'created_by' => auth()->id()
        ]);
    }

    public function getKey($keyId)
    {
        $key = EncryptionKey::where('key_id', $keyId)->first();
        if (!$key || $key->status !== 'active') {
            return null;
        }

        $this->logAccess($keyId);
        return Crypt::decryptString($key->encrypted_key);
    }

    public function rotateKey($keyId, $newKeyData)
    {
        $oldKey = EncryptionKey::where('key_id', $keyId)->first();
        $oldKey->status = 'revoked';
        $oldKey->revoked_at = now();
        $oldKey->save();

        return $this->storeKey($newKeyData);
    }

    public function revokeKey($keyId, $reason)
    {
        $key = EncryptionKey::where('key_id', $keyId)->first();
        $key->status = 'revoked';
        $key->revoked_at = now();
        $key->revocation_reason = $reason;
        $key->save();

        return $key;
    }

    protected function generateKeyId()
    {
        return 'key_' . uniqid() . '_' . bin2hex(random_bytes(8));
    }

    protected function logAccess($keyId)
    {
        KeyLog::create([
            'encryption_key_id' => $keyId,
            'action' => 'access',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'performed_at' => now()
        ]);
    }
}