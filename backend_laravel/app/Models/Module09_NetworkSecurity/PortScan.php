<?php

namespace App\Models\Module09_NetworkSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortScan extends Model
{
    use HasFactory;

    protected $table = 'port_scans';

    protected $fillable = [
        'target_ip', 'scan_type', 'open_ports', 'closed_ports',
        'filtered_ports', 'scan_duration', 'scanned_at'
    ];

    protected $casts = [
        'open_ports' => 'array',
        'closed_ports' => 'array',
        'filtered_ports' => 'array',
        'scanned_at' => 'datetime',
    ];
}