<?php

namespace App\Http\Controllers\Api\V1\Module26_AIEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AI\ChatbotService;

class AIChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Ask question
     */
    public function ask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'context' => 'nullable|string',
            'conversation_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $response = $this->chatbotService->ask(
            $request->question,
            $request->context,
            $request->conversation_id
        );

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Feedback
     */
    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required|string',
            'rating' => 'required|integer|min=1|max=5',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->chatbotService->submitFeedback($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted'
        ]);
    }

    /**
     * Conversations
     */
    public function conversations(Request $request)
    {
        $conversations = $this->chatbotService->getConversations([
            'user_id' => auth()->id(),
            'limit' => $request->limit ?? 20,
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }

    /**
     * Knowledge base
     */
    public function knowledgeBase(Request $request)
    {
        $knowledge = $this->chatbotService->getKnowledgeBase([
            'category' => $request->category,
            'search' => $request->search,
        ]);

        return response()->json([
            'success' => true,
            'data' => $knowledge
        ]);
    }

    /**
     * Train chatbot
     */
    public function trainChatbot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documents' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->chatbotService->train($request->documents);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Training started'
        ]);
    }
}