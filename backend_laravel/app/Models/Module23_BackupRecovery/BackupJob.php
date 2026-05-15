<?php

namespace App\Models\Module23_BackupRecovery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupJob extends Model
{
    use HasFactory;

    protected $table = 'backup_jobs';

    protected $fillable = [
        'job_name', 'backup_type', 'source_path', 'destination_path',
        'schedule', 'retention_days', 'compression', 'encryption',
        'last_run_at', 'next_run_at', 'status', 'created_by'
    ];

    protected $casts = [
        'compression' => 'boolean',
        'encryption' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function files()
    {
        return $this->hasMany(BackupFile::class);
    }
}