<?php

namespace App\Models\Module24_DevSecOps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sbom extends Model
{
    use HasFactory;

    protected $table = 'sboms';

    protected $fillable = [
        'application_name', 'version', 'components', 'dependencies',
        'vulnerabilities', 'generated_by', 'generated_at', 'format'
    ];

    protected $casts = [
        'components' => 'array',
        'dependencies' => 'array',
        'vulnerabilities' => 'array',
        'generated_at' => 'datetime',
    ];
}