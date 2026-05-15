<?php

namespace App\Models\Module10_EndpointSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    use HasFactory;

    protected $table = 'endpoints';

    protected $fillable = [
        'hostname', 'ip_address', 'mac_address', 'os_type', 'os_version',
        'status', 'last_seen_at', 'user_id', 'department'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function antivirusLogs()
    {
        return $this->hasMany(AntivirusLog::class);
    }

    public function patchStatuses()
    {
        return $this->hasMany(PatchStatus::class);
    }
}