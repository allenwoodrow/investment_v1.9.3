/**
 * Types for AI integration
 */

// AI Chat Message
export interface AIMessage {
    role: 'user' | 'assistant' | 'system'
    content: string
    timestamp?: Date
}

// AI Chat Response from API
export interface AIChatResponse {
    status: boolean
    data: {
        response: string
        messages?: AIMessage[]
    }
    message?: string
}

// Investment Advice Request
export interface InvestmentAdviceRequest {
    risk_level: string
    investment_horizon: string
    investment_amount: number
    goals: string
}

// API Response
export interface APIResponse<T> {
    status: boolean
    data: T
    message?: string
}