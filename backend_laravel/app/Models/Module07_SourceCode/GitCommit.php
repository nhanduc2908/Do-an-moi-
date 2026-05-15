<?php

namespace App\Models\Module07_SourceCode;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GitCommit extends Model
{
    use HasFactory;

    protected $table = 'git_commits';

    protected $fillable = [
        'commit_hash', 'author', 'author_email', 'committed_at',
        'message', 'files_changed', 'lines_added', 'lines_deleted'
    ];

    protected $casts = [
        'committed_at' => 'datetime',
        'files_changed' => 'array',
    ];
}