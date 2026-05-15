<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\CriteriaSuggestionService;

class CriteriaSuggestionController extends Controller
{
    protected $suggestionService;

    public function __construct(CriteriaSuggestionService $suggestionService)
    {
        $this->suggestionService = $suggestionService;
    }

    /**
     * Suggest criteria
     */
    public function suggestCriteria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
            'context' => 'nullable|string',
            'limit' => 'nullable|integer|min=1|max=50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $suggestions = $this->suggestionService->suggest(
            $request->domain,
            $request->context,
            $request->limit ?? 10
        );

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Evaluate suggestion
     */
    public function evaluateSuggestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluation = $this->suggestionService->evaluate($request->criteria_id);

        return response()->json([
            'success' => true,
            'data' => $evaluation
        ]);
    }

    /**
     * Recommendations
     */
    public function recommendations(Request $request)
    {
        $recommendations = $this->suggestionService->getRecommendations([
            'domain' => $request->domain,
            'priority' => $request->priority,
        ]);

        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }

    /**
     * Approve suggestion
     */
    public function approveSuggestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|string',
            'action' => 'required|in:approve,reject,modify',
            'modified_content' => 'required_if:action,modify|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->suggestionService->approve(
            $request->criteria_id,
            $request->action,
            $request->modified_content
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Suggestion processed' : 'Processing failed'
        ]);
    }
}