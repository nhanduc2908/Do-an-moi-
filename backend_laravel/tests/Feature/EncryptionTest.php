<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Module02_Encryption\AesService;
use App\Services\Module02_Encryption\RsaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EncryptionTest extends TestCase
{
    public function test_aes_encryption_and_decryption()
    {
        $aesService = new AesService();
        $originalData = 'Sensitive data to encrypt';
        
        $encrypted = $aesService->encrypt($originalData);
        $decrypted = $aesService->decrypt($encrypted);
        
        $this->assertEquals($originalData, $decrypted);
    }

    public function test_rsa_encryption_and_decryption()
    {
        $rsaService = new RsaService();
        $keyPair = $rsaService->generateKeyPair();
        
        $originalData = 'Confidential message';
        $encrypted = $rsaService->encrypt($originalData, $keyPair['public']);
        $decrypted = $rsaService->decrypt($encrypted, $keyPair['private']);
        
        $this->assertEquals($originalData, $decrypted);
    }

    public function test_rsa_sign_and_verify()
    {
        $rsaService = new RsaService();
        $keyPair = $rsaService->generateKeyPair();
        
        $data = 'Document content';
        $signature = $rsaService->sign($data, $keyPair['private']);
        
        $isValid = $rsaService->verify($data, $signature, $keyPair['public']);
        
        $this->assertTrue($isValid);
    }
}