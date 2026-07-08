<template>
    <div class="chat-interface">
        <div class="chat-header p-3 flex justify-between items-center border-bottom-1 border-gray-200">
            <h3 class="text-lg font-semibold mb-0">Investment Assistant</h3>
            <Button 
                icon="pi pi-trash" 
                class="p-button-text p-button-sm" 
                @click="clearChat" 
                :disabled="isLoading || messages.length === 0"
                tooltip="Clear conversation"
                tooltipOptions="top"
            />
        </div>
        
        <div class="chat-messages p-3" ref="messagesContainer">
            <div v-if="messages.length === 0" class="flex flex-column align-items-center justify-content-center h-full">
                <i class="pi pi-comments text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-center">
                    Ask me anything about investments, stocks, and financial markets!
                </p>
            </div>
            
            <ChatMessage 
                v-for="(msg, index) in messages" 
                :key="index"
                :role="msg.role"
                :content="msg.content"
                :timestamp="msg.timestamp || new Date()"
            />
            
            <div v-if="isLoading" class="chat-loading flex items-center">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        
        <div class="chat-input p-3 border-top-1 border-gray-200">
            <div class="p-inputgroup">
                <InputText 
                    v-model="currentMessage" 
                    placeholder="Ask about investments or financial advice..." 
                    @keydown.enter="sendMessage"
                    :disabled="isLoading"
                    class="w-full"
                />
                <Button 
                    icon="pi pi-send" 
                    @click="sendMessage" 
                    :disabled="isLoading || !currentMessage.trim()"
                />
            </div>
            <small class="text-gray-500 mt-2 block">
                Only investment and financial market topics are supported.
            </small>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, nextTick, watch } from 'vue'
import { useAIService, notify } from '../../lib'
import type { AIMessage } from '../../types/ai'
import ChatMessage from './ChatMessage.vue'

export default defineComponent({
    name: 'ChatInterface',
    components: {
        ChatMessage
    },
    props: {
        initialMessage: {
            type: String,
            default: ''
        }
    },
    setup(props) {
        const messages = ref<AIMessage[]>([])
        const currentMessage = ref('')
        const isLoading = ref(false)
        const messagesContainer = ref<HTMLElement | null>(null)
        const aiService = useAIService()
        
        // Scroll to bottom of chat
        const scrollToBottom = async () => {
            await nextTick()
            if (messagesContainer.value) {
                messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
            }
        }
        
        // Send message to AI
        const sendMessage = async () => {
            if (isLoading.value || !currentMessage.value.trim()) return
            
            const userMessage: AIMessage = {
                role: 'user',
                content: currentMessage.value,
                timestamp: new Date()
            }
            
            messages.value.push(userMessage)
            const messageToSend = currentMessage.value
            currentMessage.value = ''
            isLoading.value = true
            
            try {
                await scrollToBottom()
                
                const response = await aiService.sendChatMessage(messageToSend)
                
                if (response.status) {
                    const assistantMessage: AIMessage = {
                        role: 'assistant',
                        content: response.data.response,
                        timestamp: new Date()
                    }
                    messages.value.push(assistantMessage)
                } else {
                    notify({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to get AI response. Please try again.'
                    })
                    
                    messages.value.push({
                        role: 'system',
                        content: 'Sorry, I encountered an error processing your request. Please try again.',
                        timestamp: new Date()
                    })
                }
            } catch (error) {
                console.error('Chat error:', error)
                
                // Check if error is due to authentication
                const errorMessage = error?.message || '';
                if (errorMessage.includes('Unauthorized') || errorMessage.includes('401')) {
                    notify({
                        severity: 'error',
                        summary: 'Authentication Required',
                        detail: 'Please login to use the AI assistant.'
                    })
                    
                    messages.value.push({
                        role: 'system',
                        content: 'Authentication required. Please login to use the AI assistant.',
                        timestamp: new Date()
                    })
                } else {
                    notify({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to communicate with AI service. Please try again later.'
                    })
                    
                    messages.value.push({
                        role: 'system',
                        content: 'Sorry, I encountered an error processing your request. Please try again later.',
                        timestamp: new Date()
                    })
                }
            } finally {
                isLoading.value = false
                await scrollToBottom()
            }
        }
        
        // Clear chat history
        const clearChat = async () => {
            try {
                isLoading.value = true
                
                // Clear conversation in backend
                await aiService.clearConversation()
                
                // Clear locally
                messages.value = []
                
                notify({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Conversation cleared'
                })
            } catch (error) {
                console.error('Failed to clear chat:', error)
                notify({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to clear conversation'
                })
            } finally {
                isLoading.value = false
            }
        }
        
        // Process initial message if provided
        onMounted(() => {
            if (props.initialMessage) {
                currentMessage.value = props.initialMessage
                setTimeout(() => {
                    sendMessage()
                }, 500)
            }
        })
        
        // Watch messages changes to scroll to bottom
        watch(messages, () => {
            scrollToBottom()
        })
        
        return {
            messages,
            currentMessage,
            isLoading,
            messagesContainer,
            sendMessage,
            clearChat
        }
    }
})
</script>

<style scoped>
.chat-interface {
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    height: 400px;
}

.typing-indicator {
    display: inline-flex;
    align-items: center;
    background: #f3f4f6;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
}

.typing-indicator span {
    height: 0.5rem;
    width: 0.5rem;
    margin: 0 0.1rem;
    background-color: #9ca3af;
    border-radius: 50%;
    display: inline-block;
    animation: typing 1.5s infinite ease-in-out;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0% {
        transform: translateY(0px);
    }
    28% {
        transform: translateY(-5px);
    }
    44% {
        transform: translateY(0px);
    }
}
</style>