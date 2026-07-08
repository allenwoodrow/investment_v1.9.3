<?php

// Simple test script to verify LocalAI connection
// Run this with PHP CLI: php tests/localai_test.php

// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Use base URL from env or config
$baseUrl = env('LOCALAI_BASE_URL', 'http://68.168.222.24:8080');
$model = env('LOCALAI_MODEL', 'llama3');

echo "Testing connection to LocalAI server at: {$baseUrl}\n";
echo "Using model: {$model}\n";
echo "--------------------------------------------\n";

// Test connection to /models endpoint
try {
    echo "1. Testing connection to /models endpoint...\n";
    $ch = curl_init("{$baseUrl}/models");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo "ERROR: " . curl_error($ch) . "\n";
    } else {
        echo "Status code: {$statusCode}\n";
        if ($statusCode == 200) {
            $data = json_decode($response, true);
            echo "Models available: " . count($data) . "\n";
            
            echo "Model list:\n";
            foreach ($data as $modelInfo) {
                echo " - " . ($modelInfo['id'] ?? 'unnamed') . "\n";
            }
            
            $modelExists = false;
            foreach ($data as $modelInfo) {
                if (isset($modelInfo['id']) && $modelInfo['id'] === $model) {
                    $modelExists = true;
                    break;
                }
            }
            
            echo "Specified model '{$model}' " . ($modelExists ? "FOUND" : "NOT FOUND") . "\n";
        } else {
            echo "ERROR: Received status code {$statusCode}\n";
            echo "Response: {$response}\n";
        }
    }
    
    curl_close($ch);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test simple chat completion
try {
    echo "2. Testing chat completion...\n";
    
    $data = [
        'model' => $model,
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant that only answers questions about investments and cryptocurrency.'
            ],
            [
                'role' => 'user',
                'content' => 'What is the best way to diversify a crypto portfolio?'
            ]
        ],
        'temperature' => 0.7,
        'max_tokens' => 800
    ];
    
    $ch = curl_init("{$baseUrl}/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increase timeout for model response
    
    $start = microtime(true);
    $response = curl_exec($ch);
    $elapsed = microtime(true) - $start;
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo "ERROR: " . curl_error($ch) . "\n";
    } else {
        echo "Status code: {$statusCode}\n";
        echo "Response time: " . round($elapsed, 2) . " seconds\n";
        
        if ($statusCode == 200) {
            $data = json_decode($response, true);
            $content = $data['choices'][0]['message']['content'] ?? 'No content returned';
            $tokens = $data['usage']['total_tokens'] ?? 'unknown';
            
            echo "Tokens used: {$tokens}\n";
            echo "Response: " . substr($content, 0, 150) . "...\n";
        } else {
            echo "ERROR: Received status code {$statusCode}\n";
            echo "Response: {$response}\n";
        }
    }
    
    curl_close($ch);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n--------------------------------------------\n";
echo "Test completed.\n";