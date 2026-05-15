<?php

namespace App\Models\Module08_DatabaseSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensitiveData extends Model
{
    use HasFactory;

    protected $table = 'sensitive_data';

    protected $fillable = [
        'table_name', 'column_name', 'data_type', 'classification',
        'encryption_status', 'masking_rule', 'discovered_at'
    ];

    protected $casts = [
        'discovered_at' => 'datetime',
    ];
}