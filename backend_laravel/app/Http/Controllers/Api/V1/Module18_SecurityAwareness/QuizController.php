<?php

namespace App\Http\Controllers\Api\V1\Module18_SecurityAwareness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\QuizService;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Danh sách quizzes
     */
    public function quizzes(Request $request)
    {
        $quizzes = $this->quizService->getQuizzes([
            'topic' => $request->topic,
            'difficulty' => $request->difficulty,
        ]);

        return response()->json([
            'success' => true,
            'data' => $quizzes
        ]);
    }

    /**
     * Quiz detail
     */
    public function quizDetail($id)
    {
        $quiz = $this->quizService->getQuizDetail($id);

        return response()->json([
            'success' => true,
            'data' => $quiz
        ]);
    }

    /**
     * Start quiz
     */
    public function startQuiz($id)
    {
        $session = $this->quizService->startQuiz($id, auth()->id());

        return response()->json([
            'success' => true,
            'data' => [
                'session_id' => $session['id'],
                'questions' => $session['questions'],
                'time_limit' => $session['time_limit'],
            ]
        ]);
    }

    /**
     * Submit answer
     */
    public function submitAnswer(Request $request, $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|string',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->quizService->submitAnswer(
            $sessionId,
            $request->question_id,
            $request->answer
        );

        return response()->json([
            'success' => true,
            'data' => [
                'correct' => $result['correct'],
                'correct_answer' => $result['correct_answer'],
                'explanation' => $result['explanation'],
            ]
        ]);
    }

    /**
     * Finish quiz
     */
    public function finishQuiz($sessionId)
    {
        $result = $this->quizService->finishQuiz($sessionId);

        return response()->json([
            'success' => true,
            'data' => [
                'score' => $result['score'],
                'percentage' => $result['percentage'],
                'pass' => $result['pass'],
                'total_questions' => $result['total'],
                'correct_count' => $result['correct'],
                'wrong_count' => $result['wrong'],
                'answers' => $result['answers'],
            ]
        ]);
    }

    /**
     * Create quiz (admin)
     */
    public function createQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max=200',
            'description' => 'nullable|string',
            'topic' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'time_limit' => 'nullable|integer|min=1|max=120',
            'passing_score' => 'required|integer|min=0|max=100',
            'questions' => 'required|array|min=1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min=2',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.explanation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = $this->quizService->createQuiz($request->all());

        return response()->json([
            'success' => true,
            'data' => $quiz,
            'message' => 'Tạo quiz thành công'
        ]);
    }

    /**
     * Quiz history
     */
    public function quizHistory(Request $request)
    {
        $history = $this->quizService->getUserQuizHistory(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Leaderboard
     */
    public function leaderboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|string',
            'limit' => 'nullable|integer|min=1|max=100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $leaderboard = $this->quizService->getLeaderboard(
            $request->quiz_id,
            $request->limit ?? 20
        );

        return response()->json([
            'success' => true,
            'data' => $leaderboard
        ]);
    }
}