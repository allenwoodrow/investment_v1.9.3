<template>
    <div class="chat-message" :class="messageClass">
        <div class="flex items-start">
            <div v-if="role === 'assistant'" class="mr-2">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                    <i class="pi pi-robot"></i>
                </div>
            </div>
            <div v-else class="mr-2">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                    <i class="pi pi-user"></i>
                </div>
            </div>
            <div class="message-content">
                <div class="message-bubble" :class="bubbleClass">
                    <p class="mb-0 whitespace-pre-wrap">{{ content }}</p>
                </div>
                <small class="text-xs text-gray-500 ml-2">{{ formattedTime }}</small>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'ChatMessage',
    props: {
        role: {
            type: String,
            required: true,
            validator: (value: string) => ['user', 'assistant', 'system'].includes(value)
        },
        content: {
            type: String,
            required: true
        },
        timestamp: {
            type: Date,
            default: () => new Date()
        }
    },
    computed: {
        messageClass() {
            return {
                'chat-message-user': this.role === 'user',
                'chat-message-assistant': this.role === 'assistant',
                'chat-message-system': this.role === 'system'
            }
        },
        bubbleClass() {
            return {
                'bg-blue-100 text-blue-800': this.role === 'user',
                'bg-gray-100 text-gray-800': this.role === 'assistant',
                'bg-yellow-100 text-yellow-800': this.role === 'system'
            }
        },
        formattedTime() {
            return this.timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        }
    }
})
</script>

<style scoped>
.chat-message {
    margin-bottom: 1rem;
}

.message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    max-width: 80%;
    display: inline-block;
}

.chat-message-user .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.chat-message-assistant .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.chat-message-system .message-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>