<?php

namespace App\Models\Module19_MobileSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileDevice extends Model
{
    use HasFactory;

    protected $table = 'mobile_devices';

    protected $fillable = [
        'user_id', 'device_name', 'device_type', 'os_version',
        'device_id', 'imei', 'is_jailbroken', 'is_compliant',
        'last_compliance_check', 'last_seen_at', 'status'
    ];

    protected $casts = [
        'is_jailbroken' => 'boolean',
        'is_compliant' => 'boolean',
        'last_compliance_check' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }

    public function wipeLogs()
    {
        return $this->hasMany(RemoteWipeLog::class);
    }
}