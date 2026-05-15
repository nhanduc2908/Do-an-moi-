<?php

namespace App\Models\Module04_PasswordSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordLeak extends Model
{
    use HasFactory;

    protected $table = 'password_leaks';

    protected $fillable = [
        'user_id', 'password_hash', 'leak_source', 'detected_at', 'is_resolved'
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];
}