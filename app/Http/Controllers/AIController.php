<?php

namespace App\Http\Controllers;

use App\Services\LocalAIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class AIController extends Controller
{
    /**
     * LocalAI service
     */
    protected LocalAIService $aiService;
    
    /**
     * Create a new controller instance.
     *
     * @param LocalAIService $aiService
     * @return void
     */
    public function __construct(LocalAIService $aiService)
    {
        $this->aiService = $aiService;
    }
    
    /**
     * Handle a chat message
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function chat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Use session ID for maintaining conversation context
        $sessionId = Session::getId();
        $response = $this->aiService->chat($request->message, $sessionId);
        
        return response()->json($response);
    }
    
    /**
     * Clear conversation history
     *
     * @return JsonResponse
     */
    public function clearConversation(): JsonResponse
    {
        $sessionId = Session::getId();
        $success = $this->aiService->clearConversation($sessionId);
        
        return response()->json([
            'status' => $success,
            'message' => $success ? 'Conversation cleared' : 'Failed to clear conversation'
        ]);
    }
    
    /**
     * Get investment advice
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInvestmentAdvice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'risk_level' => 'required|string|in:low,medium,high',
            'investment_horizon' => 'required|string|in:short,medium,long',
            'investment_amount' => 'required|numeric|min:1|max:10000000',
            'goals' => 'required|string|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $response = $this->aiService->getInvestmentAdvice($request->all());
        
        return response()->json($response);
    }
    
    /**
     * Get market analysis
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMarketAnalysis(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:100'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $response = $this->aiService->getMarketAnalysis($request->query);
        
        return response()->json($response);
    }

    /**
     * Test connection to LocalAI service
     *
     * @return JsonResponse
     */
    public function testConnection(): JsonResponse
    {
        try {
            // Get LocalAI server info
            $baseUrl = Config::get('ai.base_url');
            $model = Config::get('ai.model');
            
            // Try a health check to the LocalAI server
            $response = Http::timeout(10)->get("$baseUrl/models");
            
            if ($response->successful()) {
                $modelList = $response->json();
                $status = true;
                $message = 'LocalAI server connection successful';
                
                // Check if the configured model exists
                $modelExists = false;
                $availableModels = [];
                
                foreach ($modelList as $modelInfo) {
                    if (isset($modelInfo['id']) && $modelInfo['id'] === $model) {
                        $modelExists = true;
                    }
                    if (isset($modelInfo['id'])) {
                        $availableModels[] = $modelInfo['id'];
                    }
                }
                
                $data = [
                    'server_url' => $baseUrl,
                    'configured_model' => $model,
                    'model_exists' => $modelExists,
                    'available_models' => $availableModels,
                    'response_time_ms' => $response->handlerStats()['total_time'] * 1000,
                    'server_info' => $modelList
                ];
            } else {
                $status = false;
                $message = 'LocalAI server responded with error: ' . $response->status();
                $data = [
                    'server_url' => $baseUrl,
                    'status_code' => $response->status(),
                    'error' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            $status = false;
            $message = 'Failed to connect to LocalAI server: ' . $e->getMessage();
            $data = [
                'server_url' => Config::get('ai.base_url'),
                'error' => $e->getMessage()
            ];
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}