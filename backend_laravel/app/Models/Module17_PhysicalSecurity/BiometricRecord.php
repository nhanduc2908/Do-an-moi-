<?php

namespace App\Models\Module17_PhysicalSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricRecord extends Model
{
    use HasFactory;

    protected $table = 'biometric_records';

    protected $fillable = [
        'user_id', 'biometric_type', 'template_hash', 'enrolled_at',
        'expires_at', 'status', 'device_id'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}