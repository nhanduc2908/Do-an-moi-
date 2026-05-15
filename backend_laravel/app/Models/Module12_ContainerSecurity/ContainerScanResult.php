<?php

namespace App\Models\Module12_ContainerSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KubernetesCluster extends Model
{
    use HasFactory;

    protected $table = 'kubernetes_clusters';

    protected $fillable = [
        'cluster_name', 'version', 'nodes_count', 'namespaces',
        'security_score', 'last_audit_at', 'is_compliant'
    ];

    protected $casts = [
        'namespaces' => 'array',
        'last_audit_at' => 'datetime',
        'is_compliant' => 'boolean',
    ];
}