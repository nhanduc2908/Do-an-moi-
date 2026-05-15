<?php

namespace App\Models\Module11_CloudSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudIamPolicy extends Model
{
    use HasFactory;

    protected $table = 'cloud_iam_policies';

    protected $fillable = [
        'policy_id', 'policy_name', 'provider', 'policy_document',
        'attached_to', 'is_active', 'created_at_cloud'
    ];

    protected $casts = [
        'policy_document' => 'array',
        'attached_to' => 'array',
        'created_at_cloud' => 'datetime',
        'is_active' => 'boolean',
    ];
}