<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AIService;
use App\Models\Criteria;

class AdminAIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->aiService = $aiService;
    }

    /**
     * Dashboard AI
     */
    public function index()
    {
        $modelStatus = $this->aiService->getModelStatus();
        $recentGenerations = \App\Models\AIGeneration::latest()->limit(10)->get();

        return view('admin.ai.index', compact('modelStatus', 'recentGenerations'));
    }

    /**
     * Generate criteria
     */
    public function generateCriteria(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'count' => 'required|integer|min:1|max:50',
            'complexity' => 'nullable|in:simple,medium,complex',
        ]);

        $criteria = $this->aiService->generateCriteria([
            'domain_id' => $request->domain_id,
            'count' => $request->count,
            'complexity' => $request->complexity ?? 'medium',
        ]);

        return response()->json([
            'success' => true,
            'data' => $criteria,
            'message' => "Generated {$request->count} criteria successfully."
        ]);
    }

    /**
     * Analyze threat
     */
    public function analyzeThreat(Request $request)
    {
        $request->validate([
            'data' => 'required|string',
            'data_type' => 'required|in:log,network,file',
        ]);

        $analysis = $this->aiService->analyzeThreat($request->data, $request->data_type);

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }

    /**
     * Predict risk
     */
    public function predictRisk(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|string',
        ]);

        $prediction = $this->aiService->predictRisk($request->asset_id);

        return response()->json([
            'success' => true,
            'data' => $prediction
        ]);
    }

    /**
     * Chat with AI
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|string',
        ]);

        $response = $this->aiService->chat($request->message, $request->context);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Train model
     */
    public function train(Request $request)
    {
        $request->validate([
            'model_type' => 'required|in:threat,risk,phishing',
            'dataset' => 'required|string',
            'epochs' => 'nullable|integer|min:1|max:1000',
        ]);

        $result = $this->aiService->trainModel([
            'model_type' => $request->model_type,
            'dataset' => $request->dataset,
            'epochs' => $request->epochs ?? 100,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Training started.'
        ]);
    }

    /**
     * Model performance
     */
    public function modelPerformance(Request $request)
    {
        $request->validate([
            'model_id' => 'required|string',
        ]);

        $performance = $this->aiService->getModelPerformance($request->model_id);

        return response()->json([
            'success' => true,
            'data' => $performance
        ]);
    }

    /**
     * AI settings
     */
    public function settings(Request $request)
    {
        $settings = $this->aiService->getSettings();

        if ($request->isMethod('post')) {
            $request->validate([
                'api_key' => 'nullable|string',
                'model' => 'nullable|string',
                'max_tokens' => 'nullable|integer|min:100|max:4000',
                'temperature' => 'nullable|numeric|min:0|max:1',
            ]);

            $settings = $this->aiService->updateSettings($request->all());

            return back()->with('success', 'AI settings updated.');
        }

        return view('admin.ai.settings', compact('settings'));
    }

    /**
     * Test AI connection
     */
    public function testConnection()
    {
        $result = $this->aiService->testConnection();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'AI service connected.' : 'Connection failed.'
        ]);
    }

    /**
     * AI logs
     */
    public function logs(Request $request)
    {
        $logs = \App\Models\AILog::orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.ai.logs', compact('logs'));
    }

    /**
     * Clear AI cache
     */
    public function clearCache()
    {
        $result = $this->aiService->clearCache();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'AI cache cleared.' : 'Clear failed.'
        ]);
    }
}