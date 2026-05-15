<?php

namespace App\Models\Module12_ContainerSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerImage extends Model
{
    use HasFactory;

    protected $table = 'container_images';

    protected $fillable = [
        'image_name', 'image_tag', 'image_digest', 'registry', 'size',
        'layers', 'created_at_image', 'is_vulnerable'
    ];

    protected $casts = [
        'layers' => 'array',
        'created_at_image' => 'datetime',
        'is_vulnerable' => 'boolean',
    ];

    public function scanResults()
    {
        return $this->hasMany(ContainerScanResult::class);
    }
}