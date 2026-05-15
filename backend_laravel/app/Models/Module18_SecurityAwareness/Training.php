<?php

namespace App\Models\Module18_SecurityAwareness;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'title', 'description', 'category', 'duration_minutes',
        'difficulty_level', 'content_url', 'is_mandatory',
        'created_by', 'expiry_days'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'created_by');
    }

    public function results()
    {
        return $this->hasMany(TrainingResult::class);
    }
}