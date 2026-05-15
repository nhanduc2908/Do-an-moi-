<?php

namespace App\Models\Module17_PhysicalSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CctvFootage extends Model
{
    use HasFactory;

    protected $table = 'cctv_footages';

    protected $fillable = [
        'camera_id', 'camera_name', 'location', 'file_path',
        'duration_seconds', 'file_size', 'recorded_at', 'has_motion'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'has_motion' => 'boolean',
    ];
}