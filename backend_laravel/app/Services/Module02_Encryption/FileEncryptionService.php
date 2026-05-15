<?php

namespace App\Services\Module02_Encryption;

use Illuminate\Http\UploadedFile;
use App\Models\Module02_Encryption\EncryptedFile;

class FileEncryptionService
{
    protected $aesService;
    protected $keyVaultService;

    public function __construct(AesService $aesService, KeyVaultService $keyVaultService)
    {
        $this->aesService = $aesService;
        $this->keyVaultService = $keyVaultService;
    }

    public function encryptFile(UploadedFile $file, $keyId = null)
    {
        $key = $keyId 
            ? $this->keyVaultService->getKey($keyId)
            : $this->aesService->generateKey();

        $content = file_get_contents($file->path());
        $encrypted = $this->aesService->encrypt($content);

        $path = storage_path('app/encrypted/' . $file->getClientOriginalName() . '.enc');
        file_put_contents($path, $encrypted);

        return EncryptedFile::create([
            'original_name' => $file->getClientOriginalName(),
            'encrypted_name' => $file->getClientOriginalName() . '.enc',
            'path' => $path,
            'size' => $file->getSize(),
            'algorithm' => 'AES-256-GCM',
            'encryption_key_id' => $keyId,
            'metadata' => ['mime' => $file->getMimeType()]
        ]);
    }

    public function decryptFile($encryptedFileId, $outputPath = null)
    {
        $file = EncryptedFile::findOrFail($encryptedFileId);
        
        if ($file->encryption_key_id) {
            $key = $this->keyVaultService->getKey($file->encryption_key_id);
        } else {
            $key = null;
        }

        $encryptedContent = file_get_contents($file->path);
        $decrypted = $this->aesService->decrypt($encryptedContent);

        $outputPath = $outputPath ?? storage_path('app/decrypted/' . $file->original_name);
        file_put_contents($outputPath, $decrypted);

        return $outputPath;
    }
}