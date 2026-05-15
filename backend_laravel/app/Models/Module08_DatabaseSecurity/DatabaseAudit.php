<?php

namespace App\Models\Module08_DatabaseSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseAudit extends Model
{
    use HasFactory;

    protected $table = 'database_audits';

    protected $fillable = [
        'database_name', 'table_name', 'action', 'user', 'query',
        'affected_rows', 'ip_address', 'executed_at', 'details'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'details' => 'array',
    ];
}