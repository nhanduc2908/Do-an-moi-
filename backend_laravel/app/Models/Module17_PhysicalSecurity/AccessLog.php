<?php

namespace App\Models\Module17_PhysicalSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $table = 'access_logs';

    protected $fillable = [
        'user_id', 'door_id', 'access_type', 'access_method',
        'access_granted', 'reason', 'ip_address', 'accessed_at'
    ];

    protected $casts = [
        'access_granted' => 'boolean',
        'accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}