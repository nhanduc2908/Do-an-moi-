<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Module02_Encryption\KeyVaultService;

class RotateEncryptionKeysJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $keyId;

    public function __construct($keyId = null)
    {
        $this->keyId = $keyId;
    }

    public function handle(KeyVaultService $keyVaultService)
    {
        if ($this->keyId) {
            $newKey = $keyVaultService->rotateKey($this->keyId, [
                'type' => 'AES',
                'size' => 256,
                'purpose' => 'encryption'
            ]);
            \Log::info('Key rotated', ['old_key' => $this->keyId, 'new_key' => $newKey->key_id]);
        } else {
            // Rotate all expired keys
            $this->rotateAllExpiredKeys($keyVaultService);
        }
    }

    protected function rotateAllExpiredKeys($keyVaultService)
    {
        // Logic to rotate all expired keys
    }
}