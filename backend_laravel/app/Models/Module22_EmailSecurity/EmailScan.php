<?php

namespace App\Models\Module22_EmailSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailScan extends Model
{
    use HasFactory;

    protected $table = 'email_scans';

    protected $fillable = [
        'email_id', 'sender', 'recipient', 'subject',
        'scan_status', 'threat_level', 'threat_type',
        'attachments', 'links', 'scanned_at', 'is_malicious'
    ];

    protected $casts = [
        'attachments' => 'array',
        'links' => 'array',
        'scanned_at' => 'datetime',
        'is_malicious' => 'boolean',
    ];
}