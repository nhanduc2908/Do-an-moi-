<?php

namespace App\Models\Module02_Encryption;

use App\Models\Module01_IAM\User;
use Illuminate\Database\Eloquent\Model;

class DocumentAccessLog extends Model
{
    protected $table = 'document_access_logs';

    protected $fillable = [
        'document_id', 'user_id', 'action', 'ip_address',
        'user_agent', 'access_granted', 'denial_reason',
        'justification', 'accessed_at'
    ];

    protected $casts = [
        'access_granted' => 'boolean',
        'accessed_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(EncryptedDocument::class, 'document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}