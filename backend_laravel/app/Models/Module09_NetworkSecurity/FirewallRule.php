<?php

namespace App\Models\Module09_NetworkSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirewallRule extends Model
{
    use HasFactory;

    protected $table = 'firewall_rules';

    protected $fillable = [
        'name', 'source_ip', 'destination_ip', 'protocol', 'port',
        'action', 'priority', 'is_enabled', 'description', 'expires_at'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'expires_at' => 'datetime',
    ];
}