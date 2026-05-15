<?php

namespace App\Models\Module03_WebSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XssLog extends Model
{
    use HasFactory;

    protected $table = 'xss_logs';

    protected $fillable = [
        'web_scan_result_id', 'url', 'parameter', 'payload', 
        'type', 'risk_level', 'details'
    ];
}