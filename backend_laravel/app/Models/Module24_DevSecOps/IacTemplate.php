<?php

namespace App\Models\Module24_DevSecOps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IacTemplate extends Model
{
    use HasFactory;

    protected $table = 'iac_templates';

    protected $fillable = [
        'template_name', 'template_type', 'content', 'version',
        'misconfigurations', 'is_secure', 'last_scanned_at'
    ];

    protected $casts = [
        'misconfigurations' => 'array',
        'is_secure' => 'boolean',
        'last_scanned_at' => 'datetime',
    ];
}