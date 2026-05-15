<?php

namespace App\Models\Module08_DatabaseSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SqlQueryLog extends Model
{
    use HasFactory;

    protected $table = 'sql_query_logs';

    protected $fillable = [
        'query_hash', 'query_text', 'execution_time', 'rows_affected',
        'user_id', 'ip_address', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}