<?php

namespace App\Models\Module22_EmailSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmarcRecord extends Model
{
    use HasFactory;

    protected $table = 'dmarc_records';

    protected $fillable = [
        'domain', 'record', 'policy', 'subdomain_policy',
        'percentage', 'report_emails', 'is_valid', 'last_checked_at'
    ];

    protected $casts = [
        'report_emails' => 'array',
        'is_valid' => 'boolean',
        'last_checked_at' => 'datetime',
    ];
}