<?php

namespace App\Models\Module18_SecurityAwareness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingResult extends Model
{
    use HasFactory;

    protected $table = 'training_results';

    protected $fillable = [
        'training_id', 'user_id', 'score', 'passed', 'completed_at',
        'certificate_url', 'attempts'
    ];

    protected $casts = [
        'passed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}