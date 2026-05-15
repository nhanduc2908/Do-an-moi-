<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpWhitelist extends Model
{
    use HasFactory;

    protected $table = 'ip_whitelists';

    protected $fillable = [
        'ip_address', 'label', 'user_id', 'is_active', 'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}