<?php

namespace App\Models\Module02_Encryption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalCertificate extends Model
{
    use HasFactory;

    protected $table = 'digital_certificates';

    protected $fillable = [
        'certificate_id', 'common_name', 'issuer', 'serial_number',
        'public_key', 'private_key', 'status', 'issued_at', 'expires_at',
        'revoked_at', 'domain', 'organization'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];
}