<?php

namespace App\Models\Module23_BackupRecovery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupFile extends Model
{
    use HasFactory;

    protected $table = 'backup_files';

    protected $fillable = [
        'backup_job_id', 'file_name', 'file_path', 'file_size',
        'checksum', 'created_at', 'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function job()
    {
        return $this->belongsTo(BackupJob::class);
    }
}