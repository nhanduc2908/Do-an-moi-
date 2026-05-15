<?php

namespace App\Models\Module23_BackupRecovery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoreLog extends Model
{
    use HasFactory;

    protected $table = 'restore_logs';

    protected $fillable = [
        'backup_file_id', 'restored_by', 'restored_to',
        'restore_reason', 'restored_at', 'duration_minutes',
        'status', 'errors'
    ];

    protected $casts = [
        'restored_at' => 'datetime',
    ];

    public function backupFile()
    {
        return $this->belongsTo(BackupFile::class);
    }

    public function restorer()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'restored_by');
    }
}