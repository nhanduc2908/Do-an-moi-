<?php

namespace App\Models\Module09_NetworkSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VpnConnection extends Model
{
    use HasFactory;

    protected $table = 'vpn_connections';

    protected $fillable = [
        'user_id', 'vpn_server', 'assigned_ip', 'connected_at',
        'disconnected_at', 'bytes_transferred', 'connection_type'
    ];

    protected $casts = [
        'connected_at' => 'datetime',
        'disconnected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}