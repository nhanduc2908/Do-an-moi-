<?php

namespace App\Models\Module10_EndpointSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatchStatus extends Model
{
    use HasFactory;

    protected $table = 'patch_statuses';

    protected $fillable = [
        'endpoint_id', 'patch_id', 'patch_name', 'installed_version',
        'required_version', 'status', 'installed_at'
    ];

    protected $casts = [
        'installed_at' => 'datetime',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}