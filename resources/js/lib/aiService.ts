import { useNetwork } from './request'
import type { 
    AIMessage, 
    AIChatResponse, 
    InvestmentAdviceRequest, 
    APIResponse
} from '../types/ai'

/**
 * AI Service for handling AI-related API requests
 */
export const useAIService = () => {
    const api = useNetwork()
    
    /**
     * Send a chat message to the AI
     * @param message User's message
     * @returns API response with AI's reply
     */
    const sendChatMessage = async (message: string): Promise<AIChatResponse> => {
        try {
            const response = await api.post('/api/ai/chat', { message })
            return response.data
        } catch (error) {
            console.error('Error sending chat message:', error)
            return {
                status: false,
                data: {
                    response: 'Failed to communicate with AI service. Please try again later.'
                },
                message: 'API request failed'
            }
        }
    }
    
    /**
     * Clear the current conversation history
     * @returns API response
     */
    const clearConversation = async (): Promise<APIResponse<any>> => {
        try {
            const response = await api.post('/api/ai/clear')
            return response.data
        } catch (error) {
            console.error('Error clearing conversation:', error)
            return {
                status: false,
                data: {},
                message: 'Failed to clear conversation'
            }
        }
    }
    
    /**
     * Get investment advice based on user parameters
     * @param params Investment advice parameters
     * @returns API response with investment recommendations
     */
    const getInvestmentAdvice = async (params: InvestmentAdviceRequest): Promise<AIChatResponse> => {
        try {
            const response = await api.post('/api/ai/investment-advice', params)
            return response.data
        } catch (error) {
            console.error('Error getting investment advice:', error)
            return {
                status: false,
                data: {
                    response: 'Failed to get investment advice. Please try again later.'
                },
                message: 'API request failed'
            }
        }
    }
    
    /**
     * Get market analysis for a specific stock or sector
     * @param query The stock symbol or sector to analyze
     * @returns API response with market analysis
     */
    const getMarketAnalysis = async (query: string): Promise<AIChatResponse> => {
        try {
            const response = await api.post('/api/ai/market-analysis', { query })
            return response.data
        } catch (error) {
            console.error('Error getting market analysis:', error)
            return {
                status: false,
                data: {
                    response: 'Failed to get market analysis. Please try again later.'
                },
                message: 'API request failed'
            }
        }
    }
    
    /**
     * Test connection to the AI backend
     * @returns API response with connection diagnostic info 
     */
    const testConnection = async (): Promise<APIResponse<any>> => {
        try {
            const response = await api.get('/api/ai/test')
            console.log('AI Server Test Response:', response.data)
            return response.data
        } catch (error) {
            console.error('Error testing AI connection:', error)
            return {
                status: false,
                data: {
                    error: error?.message || 'Unknown error'
                },
                message: 'Failed to test AI connection'
            }
        }
    }
    
    return {
        sendChatMessage,
        clearConversation,
        getInvestmentAdvice,
        getMarketAnalysis,
        testConnection
    }
}