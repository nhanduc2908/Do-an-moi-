<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    protected $fillable = [
        'setting_key', 'setting_value', 'setting_type',
        'category', 'description', 'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];
}