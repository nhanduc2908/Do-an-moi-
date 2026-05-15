<?php

namespace App\Models\Module04_PasswordSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $table = 'otp_codes';

    protected $fillable = [
        'user_id', 'code', 'type', 'is_used', 'expires_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}