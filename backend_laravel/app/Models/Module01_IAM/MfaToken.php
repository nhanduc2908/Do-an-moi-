<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MfaToken extends Model
{
    use HasFactory;

    protected $table = 'mfa_tokens';

    protected $fillable = [
        'user_id', 'token', 'type', 'is_used', 'expires_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}