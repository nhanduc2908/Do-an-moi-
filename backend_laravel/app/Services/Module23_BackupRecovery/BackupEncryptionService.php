<?php

namespace App\Services\Module23_BackupRecovery;

use App\Services\Module02_Encryption\AesService;
use App\Services\Module02_Encryption\KeyVaultService;

class BackupEncryptionService
{
    protected $aesService;
    protected $keyVaultService;

    public function __construct(AesService $aesService, KeyVaultService $keyVaultService)
    {
        $this->aesService = $aesService;
        $this->keyVaultService = $keyVaultService;
    }

    public function encryptBackup($backupPath, $keyId = null)
    {
        $key = $keyId 
            ? $this->keyVaultService->getKey($keyId)
            : $this->aesService->generateKey();
        
        $encryptedPath = $backupPath . '.enc';
        
        $command = "openssl enc -aes-256-cbc -salt -in {$backupPath} -out {$encryptedPath} -pass pass:{$key}";
        exec($command);
        
        if (!$keyId) {
            $this->keyVaultService->storeKey([
                'key_material' => $key,
                'type' => 'AES',
                'size' => 256,
                'purpose' => 'backup',
                'metadata' => ['backup_file' => basename($backupPath)]
            ]);
        }
        
        unlink($backupPath);
        
        return $encryptedPath;
    }

    public function decryptBackup($encryptedPath, $keyId = null, $passphrase = null)
    {
        $key = $keyId 
            ? $this->keyVaultService->getKey($keyId)
            : $passphrase;
        
        if (!$key) {
            throw new \Exception('Decryption key or passphrase required');
        }
        
        $decryptedPath = str_replace('.enc', '', $encryptedPath);
        
        $command = "openssl enc -d -aes-256-cbc -in {$encryptedPath} -out {$decryptedPath} -pass pass:{$key}";
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('Decryption failed');
        }
        
        return $decryptedPath;
    }

    public function generateBackupKey()
    {
        $keyId = 'backup_key_' . uniqid();
        
        $this->keyVaultService->storeKey([
            'key_id' => $keyId,
            'key_material' => $this->aesService->generateKey(),
            'type' => 'AES',
            'size' => 256,
            'purpose' => 'backup',
            'metadata' => ['auto_generated' => true],
            'expires_at' => now()->addYear()
        ]);
        
        return $keyId;
    }

    public function rotateBackupKeys()
    {
        $oldKeyId = config('backup.current_key_id');
        $newKeyId = $this->generateBackupKey();
        
        // Re-encrypt existing backups with new key
        $backups = BackupFile::where('encrypted', true)->get();
        
        foreach ($backups as $backup) {
            $tempPath = $this->decryptBackup($backup->file_path, $oldKeyId);
            $newPath = $this->encryptBackup($tempPath, $newKeyId);
            
            $backup->file_path = $newPath;
            $backup->save();
        }
        
        return [
            'old_key_id' => $oldKeyId,
            'new_key_id' => $newKeyId,
            'backups_reencrypted' => $backups->count()
        ];
    }
}