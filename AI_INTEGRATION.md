# LocalAI Integration for E-Broker Platform

This document outlines how the LocalAI integration works in the E-Broker application, providing AI-powered investment advice and financial information to users.

## Architecture Overview

The integration follows these key principles:
1. All requests to the AI are proxied through the Laravel backend
2. System prompts control AI responses to ensure they're limited to investment topics
3. Conversation history is maintained for contextual responses

```
Client (Vue) → Laravel Backend → LocalAI Server → Response → Client
```

## Backend Components

### Configuration (config/ai.php)

The configuration file defines:
- LocalAI server connection details
- Model to use
- System prompts for different AI features

### LocalAIService (app/Services/LocalAIService.php)

This service handles:
- Communication with LocalAI server via API
- Managing conversation history in cache
- Formatting requests and responses

### AIController (app/Http/Controllers/AIController.php)

The controller provides API endpoints for:
- Chat messaging
- Clearing conversation history
- Getting investment advice
- Market analysis

### API Routes (routes/api.php)

Routes are protected behind JWT authentication:
- POST `/api/ai/chat`: Send a message to the AI
- POST `/api/ai/clear`: Clear conversation history
- POST `/api/ai/investment-advice`: Get investment recommendations
- POST `/api/ai/market-analysis`: Get market analysis for a stock/sector

## Frontend Components

### Type Definitions (resources/js/types/ai.ts)

Type definitions for:
- AIMessage interface
- Response types
- Request parameters

### AI Service (resources/js/lib/aiService.ts)

Frontend service for API communication with methods for:
- Sending chat messages
- Clearing conversations
- Getting investment advice
- Getting market analysis

### Vue Components

1. **ChatMessage.vue**:
   - Individual message component
   - Displays user/assistant messages with styling

2. **ChatInterface.vue**:
   - Complete chat interface
   - Handles message history, loading states, scrolling

3. **InvestmentAdvisor.vue**:
   - Form to collect investment parameters
   - Displays personalized investment advice

## Usage Examples

### Basic Chat

```typescript
import { useAIService } from '../lib';

// Inside a Vue component
setup() {
  const aiService = useAIService();
  
  const sendMessage = async () => {
    const response = await aiService.sendChatMessage('What are the best stocks to invest in?');
    
    if (response.status) {
      // Handle successful response
      console.log(response.data.response);
    }
  };
  
  return { sendMessage };
}
```

### Getting Investment Advice

```typescript
import { useAIService } from '../lib';
import type { InvestmentAdviceRequest } from '../types/ai';

// Inside a Vue component
setup() {
  const aiService = useAIService();
  
  const getAdvice = async () => {
    const params: InvestmentAdviceRequest = {
      risk_level: 'medium',
      investment_horizon: 'long',
      investment_amount: 10000,
      goals: 'I want to save for retirement'
    };
    
    const response = await aiService.getInvestmentAdvice(params);
    
    if (response.status) {
      // Handle advice
      console.log(response.data.response);
    }
  };
  
  return { getAdvice };
}
```

## Configuration

In your `.env` file:

```
LOCALAI_BASE_URL=http://your-localai-server:8080
LOCALAI_MODEL=llama3
```

## System Prompts

The backend uses three different system prompts to control AI behavior:

1. **Default Chat Prompt**: Ensures AI only discusses investment topics
2. **Investment Advice Prompt**: Guides AI to provide structured investment recommendations
3. **Market Analysis Prompt**: Formats responses for stock/market analysis

## Testing

Unit tests can be run with:

```bash
php artisan test --filter=LocalAIServiceTest
```