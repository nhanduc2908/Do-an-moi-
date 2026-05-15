<?php

namespace App\Models\Module22_EmailSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DkimRecord extends Model
{
    use HasFactory;

    protected $table = 'dkim_records';

    protected $fillable = [
        'domain', 'selector', 'public_key', 'algorithm',
        'is_valid', 'last_checked_at', 'expires_at'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'last_checked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}