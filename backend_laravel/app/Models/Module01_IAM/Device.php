<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'user_id', 'device_name', 'device_type', 'device_id', 
        'is_trusted', 'last_used_at', 'fingerprint'
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}