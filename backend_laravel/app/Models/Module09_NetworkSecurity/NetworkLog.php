<?php

namespace App\Models\Module09_NetworkSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkLog extends Model
{
    use HasFactory;

    protected $table = 'network_logs';

    protected $fillable = [
        'source_ip', 'destination_ip', 'protocol', 'port', 'packet_size',
        'action', 'rule_id', 'logged_at', 'details'
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'details' => 'array',
    ];
}