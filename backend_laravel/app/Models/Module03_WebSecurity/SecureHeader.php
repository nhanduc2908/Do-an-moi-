<?php

namespace App\Models\Module03_WebSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecureHeader extends Model
{
    use HasFactory;

    protected $table = 'secure_headers';

    protected $fillable = [
        'header_name', 'header_value', 'is_enabled', 'priority', 'description'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}