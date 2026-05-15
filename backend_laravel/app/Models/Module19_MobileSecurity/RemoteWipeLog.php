<?php

namespace App\Models\Module19_MobileSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoteWipeLog extends Model
{
    use HasFactory;

    protected $table = 'remote_wipe_logs';

    protected $fillable = [
        'mobile_device_id', 'initiated_by', 'wipe_type', 'status',
        'initiated_at', 'completed_at', 'reason'
    ];

    protected $casts = [
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(MobileDevice::class);
    }

    public function initiator()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'initiated_by');
    }
}