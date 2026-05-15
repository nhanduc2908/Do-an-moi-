<?php

namespace App\Services\Module18_SecurityAwareness;

use App\Models\Module18_SecurityAwareness\Training;
use App\Models\Module18_SecurityAwareness\TrainingResult;

class TrainingService
{
    public function assignTraining($userId, $trainingId, $dueDate = null)
    {
        return TrainingResult::create([
            'training_id' => $trainingId,
            'user_id' => $userId,
            'assigned_at' => now(),
            'due_date' => $dueDate ?? now()->addDays(30),
            'status' => 'assigned'
        ]);
    }

    public function completeTraining($userId, $trainingId, $score, $passed = true)
    {
        $result = TrainingResult::where('user_id', $userId)
            ->where('training_id', $trainingId)
            ->where('status', 'assigned')
            ->first();
        
        if ($result) {
            $result->score = $score;
            $result->passed = $passed;
            $result->status = 'completed';
            $result->completed_at = now();
            $result->save();
            
            $this->issueCertificate($userId, $trainingId, $result);
        }
        
        return $result;
    }

    protected function issueCertificate($userId, $trainingId, $result)
    {
        if ($result->passed) {
            $certificateUrl = $this->generateCertificate($userId, $trainingId, $result);
            $result->certificate_url = $certificateUrl;
            $result->save();
        }
    }

    protected function generateCertificate($userId, $trainingId, $result)
    {
        // Generate PDF certificate
        $path = storage_path("certificates/user_{$userId}_training_{$trainingId}.pdf");
        
        return url("/certificates/{$userId}_{$trainingId}.pdf");
    }

    public function getTrainingProgress($userId)
    {
        $trainings = Training::all();
        $completed = TrainingResult::where('user_id', $userId)
            ->where('status', 'completed')
            ->get();
        
        $progress = [
            'total_trainings' => $trainings->count(),
            'completed' => $completed->count(),
            'in_progress' => TrainingResult::where('user_id', $userId)
                ->where('status', 'assigned')
                ->count(),
            'overall_score' => $completed->avg('score') ?? 0,
            'certificates_issued' => $completed->whereNotNull('certificate_url')->count()
        ];
        
        $progress['percentage'] = $progress['total_trainings'] > 0 
            ? ($progress['completed'] / $progress['total_trainings']) * 100 
            : 0;
        
        return $progress;
    }
}