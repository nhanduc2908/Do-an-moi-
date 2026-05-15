<?php

namespace App\Models\Module22_EmailSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingEmail extends Model
{
    use HasFactory;

    protected $table = 'phishing_emails';

    protected $fillable = [
        'sender', 'sender_domain', 'recipient', 'subject',
        'body_preview', 'malicious_urls', 'reported_by',
        'reported_at', 'status', 'analysis_result'
    ];

    protected $casts = [
        'malicious_urls' => 'array',
        'reported_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'reported_by');
    }
}