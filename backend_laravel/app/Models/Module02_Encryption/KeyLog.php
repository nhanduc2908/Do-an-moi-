<?php

namespace App\Models\Module02_Encryption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyLog extends Model
{
    use HasFactory;

    protected $table = 'key_logs';

    protected $fillable = [
        'encryption_key_id', 'action', 'user_id', 'ip_address', 
        'details', 'performed_at'
    ];

    protected $casts = [
        'details' => 'array',
        'performed_at' => 'datetime',
    ];

    public function encryptionKey()
    {
        return $this->belongsTo(EncryptionKey::class);
    }
}