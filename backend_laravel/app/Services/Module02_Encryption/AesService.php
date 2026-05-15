<?php

namespace App\Services\Module02_Encryption;

class AesService
{
    private $cipher = 'aes-256-gcm';
    private $key;

    public function __construct()
    {
        $this->key = config('encryption.aes_key');
    }

    public function encrypt($data)
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
        $tag = '';

        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $encrypted);
    }

    public function decrypt($encryptedData)
    {
        $decoded = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($decoded, 0, $ivLength);
        $tag = substr($decoded, $ivLength, 16);
        $ciphertext = substr($decoded, $ivLength + 16);

        return openssl_decrypt(
            $ciphertext,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }

    public function generateKey($size = 256)
    {
        return random_bytes($size / 8);
    }
}