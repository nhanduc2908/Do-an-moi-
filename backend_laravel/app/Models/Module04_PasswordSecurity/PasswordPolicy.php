<?php

namespace App\Models\Module04_PasswordSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordPolicy extends Model
{
    use HasFactory;

    protected $table = 'password_policies';

    protected $fillable = [
        'name', 'min_length', 'require_uppercase', 'require_lowercase',
        'require_numbers', 'require_special', 'expiry_days', 
        'history_count', 'max_attempts', 'lockout_minutes'
    ];

    protected $casts = [
        'require_uppercase' => 'boolean',
        'require_lowercase' => 'boolean',
        'require_numbers' => 'boolean',
        'require_special' => 'boolean',
    ];
}