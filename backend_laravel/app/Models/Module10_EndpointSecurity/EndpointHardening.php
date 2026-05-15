<?php

namespace App\Models\Module10_EndpointSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndpointHardening extends Model
{
    use HasFactory;

    protected $table = 'endpoint_hardenings';

    protected $fillable = [
        'endpoint_id', 'check_name', 'status', 'compliance', 'last_checked_at', 'details'
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'details' => 'array',
    ];
}