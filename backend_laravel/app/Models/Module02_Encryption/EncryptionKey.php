<?php

namespace App\Models\Module02_Encryption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncryptionKey extends Model
{
    use HasFactory;

    protected $table = 'encryption_keys';

    protected $fillable = [
        'key_id', 'type', 'size', 'purpose', 'status', 'public_key',
        'private_key', 'fingerprint', 'expires_at', 'revoked_at',
        'revocation_reason', 'metadata', 'tags', 'created_by'
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'created_by');
    }

    public function encryptedFiles()
    {
        return $this->hasMany(EncryptedFile::class);
    }

    public function keyLogs()
    {
        return $this->hasMany(KeyLog::class);
    }
}