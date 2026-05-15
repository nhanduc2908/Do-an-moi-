<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    use HasFactory;

    protected $table = 'backup_settings';

    protected $fillable = [
        'backup_type', 'schedule', 'retention_count',
        'destination_path', 'is_compressed', 'is_encrypted',
        'last_backup_at', 'is_enabled'
    ];

    protected $casts = [
        'is_compressed' => 'boolean',
        'is_encrypted' => 'boolean',
        'last_backup_at' => 'datetime',
        'is_enabled' => 'boolean',
    ];
}