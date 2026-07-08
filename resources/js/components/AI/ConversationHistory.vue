<template>
  <div class="conversation-history">
    <div class="header p-3 flex justify-between items-center border-bottom-1 border-gray-200">
      <h3 class="text-lg font-semibold mb-0">Conversation History</h3>
      <Button
        icon="pi pi-refresh"
        class="p-button-text p-button-sm"
        @click="loadConversations"
        :loading="isLoading"
        tooltip="Refresh"
        tooltipOptions="top"
      />
    </div>

    <div class="p-3" v-if="isLoading">
      <ProgressSpinner style="width: 50px" strokeWidth="4" fill="var(--surface-ground)" />
      <p class="text-center text-gray-500">Loading conversations...</p>
    </div>

    <div class="p-3 text-center" v-else-if="conversations.length === 0">
      <i class="pi pi-comments text-5xl text-gray-300 mb-3"></i>
      <p class="text-gray-500">
        You don't have any conversations yet.
      </p>
    </div>

    <div class="conversations" v-else>
      <div
        v-for="conversation in conversations"
        :key="conversation.id"
        class="conversation-item p-3 cursor-pointer border-bottom-1 border-gray-200 hover:bg-gray-50"
        :class="{ 'bg-blue-50': selectedConversationId === conversation.id }"
        @click="selectConversation(conversation.id)"
      >
        <div class="flex justify-between items-start">
          <h4 class="font-medium text-md mb-2">{{ conversation.title }}</h4>
          <div class="flex">
            <Button
              icon="pi pi-trash"
              class="p-button-text p-button-sm p-button-rounded p-button-danger"
              @click.stop="deleteConversation(conversation.id)"
              tooltip="Delete"
              tooltipOptions="top"
            />
          </div>
        </div>
        <p class="text-sm text-gray-600 mb-1" v-if="conversation.first_message">
          {{ truncateText(conversation.first_message.content, 60) }}
        </p>
        <div class="flex justify-between items-center">
          <span class="text-xs text-gray-500">
            {{ formatDate(conversation.created_at) }}
          </span>
          <span class="text-xs bg-gray-200 rounded-full px-2 py-1">
            {{ conversation.message_count }} messages
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue'
import { useAIService, notify } from '../../lib'
import type { AIConversation } from '../../types/ai'

export default defineComponent({
  name: 'ConversationHistory',
  emits: ['conversation-selected'],
  
  setup(props, { emit }) {
    const conversations = ref<AIConversation[]>([])
    const isLoading = ref(false)
    const selectedConversationId = ref<number | null>(null)
    const aiService = useAIService()
    
    const loadConversations = async () => {
      isLoading.value = true
      
      try {
        const response = await aiService.getUserConversations()
        
        if (response.status) {
          conversations.value = response.data.conversations.filter(c => c.is_active)
        } else {
          notify({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load conversation history'
          })
        }
      } catch (error) {
        console.error('Error loading conversations:', error)
        notify({
          severity: 'error',
          summary: 'Error',
          detail: 'An error occurred while loading conversations'
        })
      } finally {
        isLoading.value = false
      }
    }
    
    const selectConversation = async (conversationId: number) => {
      selectedConversationId.value = conversationId
      emit('conversation-selected', conversationId)
    }
    
    const deleteConversation = async (conversationId: number) => {
      try {
        const response = await aiService.clearConversation(conversationId)
        
        if (response.status) {
          notify({
            severity: 'success',
            summary: 'Success',
            detail: 'Conversation deleted successfully'
          })
          
          // Refresh the list
          await loadConversations()
          
          // If the deleted conversation was selected, clear the selection
          if (selectedConversationId.value === conversationId) {
            selectedConversationId.value = null
            emit('conversation-selected', null)
          }
        } else {
          notify({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete conversation'
          })
        }
      } catch (error) {
        console.error('Error deleting conversation:', error)
        notify({
          severity: 'error',
          summary: 'Error',
          detail: 'An error occurred while deleting the conversation'
        })
      }
    }
    
    const truncateText = (text: string, length: number): string => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }
    
    const formatDate = (dateString: string): string => {
      const date = new Date(dateString)
      return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    }
    
    onMounted(() => {
      loadConversations()
    })
    
    return {
      conversations,
      isLoading,
      selectedConversationId,
      loadConversations,
      selectConversation,
      deleteConversation,
      truncateText,
      formatDate
    }
  }
})
</script>

<style scoped>
.conversation-history {
  height: 100%;
  display: flex;
  flex-direction: column;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.conversations {
  flex: 1;
  overflow-y: auto;
}

.conversation-item {
  transition: background-color 0.2s;
}

.conversation-item:hover {
  background-color: var(--surface-hover);
}
</style>