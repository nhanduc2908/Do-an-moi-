<?php

namespace App\Services\Module02_Encryption;

class RsaService
{
    private $privateKey;
    private $publicKey;

    public function __construct($privateKeyPath = null, $publicKeyPath = null)
    {
        if ($privateKeyPath) {
            $this->privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        }
        if ($publicKeyPath) {
            $this->publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));
        }
    }

    public function encrypt($data, $publicKey = null)
    {
        $key = $publicKey ?? $this->publicKey;
        openssl_public_encrypt($data, $encrypted, $key);
        return base64_encode($encrypted);
    }

    public function decrypt($data, $privateKey = null)
    {
        $key = $privateKey ?? $this->privateKey;
        openssl_private_decrypt(base64_decode($data), $decrypted, $key);
        return $decrypted;
    }

    public function sign($data, $privateKey = null)
    {
        $key = $privateKey ?? $this->privateKey;
        openssl_sign($data, $signature, $key, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    public function verify($data, $signature, $publicKey = null)
    {
        $key = $publicKey ?? $this->publicKey;
        $signature = base64_decode($signature);
        return openssl_verify($data, $signature, $key, OPENSSL_ALGO_SHA256) === 1;
    }

    public function generateKeyPair($bits = 2048)
    {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => $bits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        return [
            'private' => $privateKey,
            'public' => $publicKey
        ];
    }
}