<?php

namespace App\Models\Module02_Encryption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncryptedFile extends Model
{
    use HasFactory;

    protected $table = 'encrypted_files';

    protected $fillable = [
        'encryption_key_id', 'original_name', 'encrypted_name', 'path',
        'size', 'algorithm', 'iv', 'tag', 'metadata', 'expires_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    public function encryptionKey()
    {
        return $this->belongsTo(EncryptionKey::class);
    }
}