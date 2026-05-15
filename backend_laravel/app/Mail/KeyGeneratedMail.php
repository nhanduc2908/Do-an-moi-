<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KeyGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $keyData;

    public function __construct($keyData)
    {
        $this->keyData = $keyData;
    }

    public function build()
    {
        return $this->subject('New Encryption Key Generated - ' . $this->keyData['key_id'])
                    ->markdown('emails.key-generated')
                    ->with([
                        'keyId' => $this->keyData['key_id'],
                        'keyType' => $this->keyData['type'],
                        'purpose' => $this->keyData['purpose'],
                        'expiresAt' => $this->keyData['expires_at'],
                        'fingerprint' => $this->keyData['fingerprint']
                    ]);
    }
}