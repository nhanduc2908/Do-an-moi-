<?php

namespace App\Models\Module14_IncidentResponse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentComment extends Model
{
    use HasFactory;

    protected $table = 'incident_comments';

    protected $fillable = [
        'incident_id', 'user_id', 'comment', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}<?php

namespace App\Models\Module14_IncidentResponse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForensicEvidence extends Model
{
    use HasFactory;

    protected $table = 'forensic_evidences';

    protected $fillable = [
        'incident_id', 'evidence_type', 'file_path', 'hash_value',
        'collected_by', 'collected_at', 'chain_of_custody', 'description'
    ];

    protected $casts = [
        'collected_at' => 'datetime',
        'chain_of_custody' => 'array',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function collector()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'collected_by');
    }
}