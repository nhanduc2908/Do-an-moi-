<?php

namespace App\Models\Module12_ContainerSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistryImage extends Model
{
    use HasFactory;

    protected $table = 'registry_images';

    protected $fillable = [
        'registry_url', 'repository', 'image_name', 'tags', 'size',
        'pulled_count', 'last_pulled_at', 'is_signed'
    ];

    protected $casts = [
        'tags' => 'array',
        'last_pulled_at' => 'datetime',
        'is_signed' => 'boolean',
    ];
}