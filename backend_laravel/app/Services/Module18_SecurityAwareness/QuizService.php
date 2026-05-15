<?php

namespace App\Services\Module18_SecurityAwareness;

use App\Models\Module18_SecurityAwareness\QuizAttempt;

class QuizService
{
    protected $questions = [];

    public function generateQuiz($topic, $difficulty = 'medium', $questionCount = 10)
    {
        $questions = $this->fetchQuestions($topic, $difficulty, $questionCount);
        
        return [
            'quiz_id' => uniqid(),
            'topic' => $topic,
            'difficulty' => $difficulty,
            'questions' => $questions,
            'time_limit' => $questionCount * 60 // seconds per question
        ];
    }

    public function evaluateQuiz($quizId, $userId, $answers)
    {
        $questions = $this->getQuizQuestions($quizId);
        $score = 0;
        $maxScore = count($questions);
        
        foreach ($questions as $index => $question) {
            if ($answers[$index] === $question['correct_answer']) {
                $score++;
            }
        }
        
        $percentage = ($score / $maxScore) * 100;
        $passed = $percentage >= 70;
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'score' => $score,
            'max_score' => $maxScore,
            'answers' => $answers,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
            'is_passed' => $passed
        ]);
        
        return [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => $percentage,
            'passed' => $passed,
            'feedback' => $this->generateFeedback($score, $maxScore)
        ];
    }

    protected function fetchQuestions($topic, $difficulty, $count)
    {
        // Fetch from question bank
        $questions = [];
        
        return $questions;
    }

    protected function generateFeedback($score, $maxScore)
    {
        $percentage = ($score / $maxScore) * 100;
        
        if ($percentage >= 90) {
            return 'Excellent! You have strong security awareness.';
        } elseif ($percentage >= 70) {
            return 'Good job! Review the areas you missed.';
        } elseif ($percentage >= 50) {
            return 'Fair effort. Consider taking additional training.';
        } else {
            return 'Needs improvement. Please complete security training.';
        }
    }
}