<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\LocalAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocalAIServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock HTTP facade for all tests
        Http::fake([
            // Mock successful response from LocalAI chat endpoint
            config('ai.base_url') . '/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'This is a test response from the AI assistant.'
                        ]
                    ]
                ]
            ], 200),
        ]);
    }

    /** @test */
    public function it_can_send_chat_messages()
    {
        $aiService = $this->app->make(LocalAIService::class);
        
        $response = $aiService->chat('Tell me about investing in stocks');
        
        $this->assertTrue($response['status']);
        $this->assertEquals('This is a test response from the AI assistant.', $response['data']['response']);
    }
    
    /** @test */
    public function it_can_clear_conversation_history()
    {
        $aiService = $this->app->make(LocalAIService::class);
        $testUserId = 'test-user-123';
        
        // Store some test messages first
        $cacheKey = 'localai_conversation_' . $testUserId;
        $testMessages = [
            ['role' => 'system', 'content' => 'Test system message'],
            ['role' => 'user', 'content' => 'Test user message'],
            ['role' => 'assistant', 'content' => 'Test assistant response']
        ];
        Cache::put($cacheKey, $testMessages, now()->addHour());
        
        // Verify messages exist in cache
        $this->assertEquals($testMessages, Cache::get($cacheKey));
        
        // Clear the conversation
        $result = $aiService->clearConversation($testUserId);
        
        // Verify result and cache was cleared
        $this->assertTrue($result);
        $this->assertNull(Cache::get($cacheKey));
    }
    
    /** @test */
    public function it_can_get_investment_advice()
    {
        $aiService = $this->app->make(LocalAIService::class);
        
        $params = [
            'risk_level' => 'medium',
            'investment_horizon' => 'long',
            'investment_amount' => 10000,
            'goals' => 'Saving for retirement'
        ];
        
        $response = $aiService->getInvestmentAdvice($params);
        
        $this->assertTrue($response['status']);
        $this->assertEquals('This is a test response from the AI assistant.', $response['data']['response']);
    }
    
    /** @test */
    public function it_can_get_market_analysis()
    {
        $aiService = $this->app->make(LocalAIService::class);
        
        $response = $aiService->getMarketAnalysis('AAPL');
        
        $this->assertTrue($response['status']);
        $this->assertEquals('This is a test response from the AI assistant.', $response['data']['response']);
    }
}