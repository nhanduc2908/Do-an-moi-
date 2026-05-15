<?php

namespace App\Models\Module11_CloudSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudResource extends Model
{
    use HasFactory;

    protected $table = 'cloud_resources';

    protected $fillable = [
        'resource_id', 'resource_type', 'provider', 'region', 'name',
        'configuration', 'tags', 'created_at_cloud', 'is_compliant'
    ];

    protected $casts = [
        'configuration' => 'array',
        'tags' => 'array',
        'created_at_cloud' => 'datetime',
        'is_compliant' => 'boolean',
    ];
}