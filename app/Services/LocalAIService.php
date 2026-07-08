<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class LocalAIService
{
    /**
     * Base URL for the LocalAI service
     */
    protected string $baseUrl;
    
    /**
     * Model to use for completions
     */
    protected string $model;
    
    /**
     * Default system prompt
     */
    protected string $systemPrompt;
    
    /**
     * Cache key for session-based conversations (not stored in database)
     */
    protected string $cachePrefix = 'localai_chat_';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUrl = Config::get('ai.base_url');
        $this->model = Config::get('ai.model');
        $this->systemPrompt = Config::get('ai.system_prompt');
    }
    
    /**
     * Send a chat completion request to LocalAI
     *
     * @param string $message The user message
     * @param string|null $sessionId Session ID for maintaining context within a session
     * @param string|null $customSystemPrompt Optional custom system prompt
     * @return array The response from LocalAI
     */
    public function chat(string $message, ?string $sessionId = null, ?string $customSystemPrompt = null): array
    {
        try {
            // Use session ID or generate a random one for this request
            $sessionId = $sessionId ?? Session::getId();
            $cacheKey = $this->cachePrefix . $sessionId;
            
            // Get existing conversation from cache
            $messages = Cache::get($cacheKey, []);
            $isNewConversation = empty($messages);
            
            // Add system message if it's the start of the conversation
            if ($isNewConversation) {
                $systemPromptContent = $customSystemPrompt ?? $this->systemPrompt;
                $messages[] = [
                    'role' => 'system',
                    'content' => $systemPromptContent
                ];
            }
            
            // Add the user message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];
            
            // Send request to LocalAI
            $response = Http::timeout(60)->post("{$this->baseUrl}/v1/chat/completions", [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                $aiResponse = $responseData['choices'][0]['message']['content'] ?? 'No response from AI service.';
                
                // Log token usage for monitoring
                $tokens = $responseData['usage']['total_tokens'] ?? 0;
                Log::info("LocalAI Token Usage: {$tokens} tokens for session {$sessionId}");
                
                // Add AI response to conversation
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $aiResponse
                ];
                
                // Store conversation in cache (short term)
                $this->storeConversation($cacheKey, $messages);
                
                return [
                    'status' => true,
                    'data' => [
                        'response' => $aiResponse,
                        'messages' => $messages
                    ]
                ];
            } else {
                Log::error('LocalAI service error: ' . $response->body());
                return [
                    'status' => false,
                    'message' => 'Error communicating with AI service: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('LocalAI service exception: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to process AI request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get investment advice based on user parameters
     *
     * @param array $params Investment parameters
     * @return array The response
     */
    public function getInvestmentAdvice(array $params): array
    {
        $prompt = Config::get('ai.investment_advice_prompt');
        
        $message = "I need investment advice with the following parameters:\n";
        $message .= "Risk Tolerance: {$params['risk_level']}\n";
        $message .= "Investment Horizon: {$params['investment_horizon']}\n";
        $message .= "Investment Amount: \${$params['investment_amount']}\n";
        $message .= "Financial Goals: {$params['goals']}\n";
        $message .= "Please provide appropriate investment recommendations.";
        
        // Use a unique session ID for investment advice to keep context separate
        $sessionId = 'investment_advice_' . Session::getId();
        
        return $this->chat($message, $sessionId, $prompt);
    }
    
    /**
     * Get market analysis for a specific stock or sector
     *
     * @param string $query The stock symbol or sector to analyze
     * @return array The response
     */
    public function getMarketAnalysis(string $query): array
    {
        $prompt = Config::get('ai.market_analysis_prompt');
        
        $message = "Please provide a market analysis for: {$query}";
        
        // Use a unique session ID for market analysis to keep context separate
        $sessionId = 'market_analysis_' . Session::getId();
        
        return $this->chat($message, $sessionId, $prompt);
    }
    
    /**
     * Clear conversation for a session
     *
     * @param string|null $sessionId Optional session ID
     * @return bool Whether the operation was successful
     */
    public function clearConversation(?string $sessionId = null): bool
    {
        try {
            $sessionId = $sessionId ?? Session::getId();
            $cacheKey = $this->cachePrefix . $sessionId;
            
            return Cache::forget($cacheKey);
        } catch (\Exception $e) {
            Log::error('Failed to clear conversation: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Store conversation in cache
     *
     * @param string $cacheKey The cache key
     * @param array $messages The conversation messages
     * @return bool Whether the operation was successful
     */
    protected function storeConversation(string $cacheKey, array $messages): bool
    {
        // Manage context window by keeping only recent messages
        if (count($messages) > 12) {
            // Always keep the system prompt
            $systemMessage = $messages[0];
            $messages = array_slice($messages, -11);
            array_unshift($messages, $systemMessage);
        }
        
        // Cache conversation for 30 minutes
        return Cache::put($cacheKey, $messages, Carbon::now()->addMinutes(30));
    }
}