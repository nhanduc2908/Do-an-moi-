<?php

namespace App\Models\Module20_IotSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotFirmware extends Model
{
    use HasFactory;

    protected $table = 'iot_firmwares';

    protected $fillable = [
        'device_id', 'version', 'release_date', 'file_hash',
        'file_size', 'vulnerabilities', 'is_secure', 'changelog'
    ];

    protected $casts = [
        'release_date' => 'datetime',
        'vulnerabilities' => 'array',
        'is_secure' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(IotDevice::class);
    }
}