<template>
  <div class="conversation-history-view">
    <PageHeader 
      title="AI Conversation History" 
      description="View and continue your past investment conversations" 
    />

    <div class="grid mt-4">
      <!-- Conversation History Sidebar -->
      <div class="col-12 md:col-4 lg:col-3">
        <ConversationHistory @conversation-selected="handleConversationSelected" />

        <!-- Conversation Metrics -->
        <Card class="mt-4">
          <template #title>
            <div class="flex align-items-center gap-2">
              <i class="pi pi-chart-bar"></i>
              <span>Conversation Metrics</span>
            </div>
          </template>
          <template #content>
            <div v-if="isLoading" class="flex justify-content-center">
              <ProgressSpinner style="width: 40px" />
            </div>
            <div v-else>
              <div class="flex justify-content-between mb-3">
                <span class="font-medium">Total Conversations:</span>
                <span class="text-primary font-medium">{{ metrics.totalConversations }}</span>
              </div>
              <div class="flex justify-content-between mb-3">
                <span class="font-medium">Total Messages:</span>
                <span class="text-primary font-medium">{{ metrics.totalMessages }}</span>
              </div>
              <div class="flex justify-content-between">
                <span class="font-medium">Tokens Used:</span>
                <span class="text-primary font-medium">{{ metrics.totalTokens }}</span>
              </div>
            </div>
          </template>
        </Card>
      </div>

      <!-- Chat Interface -->
      <div class="col-12 md:col-8 lg:col-9">
        <div v-if="selectedConversationId" class="selected-conversation-header mb-3 p-3 border-1 surface-border border-round flex align-items-center justify-content-between">
          <div>
            <h3 class="m-0 text-xl">{{ selectedConversation?.title || 'Conversation' }}</h3>
            <p class="m-0 text-sm text-500">
              {{ selectedConversation ? formatDate(selectedConversation.created_at) : '' }}
            </p>
          </div>
          <div>
            <Button 
              icon="pi pi-trash" 
              class="p-button-rounded p-button-danger p-button-outlined mr-2"
              @click="deleteSelectedConversation"
              tooltip="Delete this conversation"
            />
            <Button 
              icon="pi pi-refresh" 
              class="p-button-rounded p-button-outlined"
              @click="refreshSelectedConversation"
              tooltip="Refresh conversation"
            />
          </div>
        </div>

        <div v-if="!selectedConversationId" class="empty-state p-5 border-1 surface-border border-round text-center">
          <i class="pi pi-comments text-6xl text-primary opacity-60 mb-3"></i>
          <h3>Select a Conversation</h3>
          <p class="text-500 mb-4">Choose a conversation from the history or start a new one</p>
          <Button 
            label="Start New Conversation" 
            icon="pi pi-plus" 
            @click="startNewConversation"
          />
        </div>

        <ChatInterface 
          v-else
          ref="chatInterface"
          :key="'chat-' + selectedConversationId"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted, nextTick } from 'vue'
import { PageHeader, ConversationHistory, ChatInterface } from '../components'
import { useAIService } from '../lib'
import type { AIConversation } from '../types/ai'

export default defineComponent({
  name: 'ConversationHistoryView',
  components: {
    PageHeader,
    ConversationHistory,
    ChatInterface
  },
  setup() {
    const aiService = useAIService()
    const selectedConversationId = ref<number | null>(null)
    const selectedConversation = ref<AIConversation | null>(null)
    const isLoading = ref(false)
    const chatInterface = ref<InstanceType<typeof ChatInterface> | null>(null)
    const conversations = ref<AIConversation[]>([])

    // Computed metrics
    const metrics = computed(() => {
      if (!conversations.value.length) {
        return {
          totalConversations: 0,
          totalMessages: 0,
          totalTokens: 0
        }
      }

      return {
        totalConversations: conversations.value.length,
        totalMessages: conversations.value.reduce((sum, conv) => sum + conv.message_count, 0),
        totalTokens: 0 // Would need to fetch this from backend in a real implementation
      }
    })

    // Load selected conversation data
    const loadSelectedConversation = async (id: number) => {
      if (!id) return
      
      try {
        const response = await aiService.getConversationMessages(id)
        if (response.status) {
          selectedConversation.value = {
            id: response.data.conversation.id,
            title: response.data.conversation.title,
            is_active: response.data.conversation.is_active,
            created_at: response.data.conversation.created_at,
            updated_at: response.data.conversation.updated_at,
            message_count: response.data.messages.length,
            first_message: response.data.messages[0]?.role === 'user' ? {
              content: response.data.messages[0].content,
              created_at: response.data.messages[0].created_at || ''
            } : undefined,
            last_message: {
              role: response.data.messages[response.data.messages.length - 1]?.role || 'system',
              content: response.data.messages[response.data.messages.length - 1]?.content || '',
              created_at: response.data.messages[response.data.messages.length - 1]?.created_at || ''
            }
          }
        }
      } catch (error) {
        console.error('Error loading conversation:', error)
      }
    }

    // Handle conversation selection
    const handleConversationSelected = async (conversationId: number | null) => {
      selectedConversationId.value = conversationId
      
      if (conversationId) {
        await loadSelectedConversation(conversationId)

        // Wait for the chat interface to be mounted
        await nextTick()
        
        // Load the conversation in the chat interface
        if (chatInterface.value) {
          chatInterface.value.handleConversationSelected(conversationId)
        }
      } else {
        selectedConversation.value = null
      }
    }

    // Delete the selected conversation
    const deleteSelectedConversation = async () => {
      if (!selectedConversationId.value) return
      
      try {
        await aiService.clearConversation(selectedConversationId.value)
        selectedConversationId.value = null
        selectedConversation.value = null
      } catch (error) {
        console.error('Error deleting conversation:', error)
      }
    }

    // Refresh the selected conversation
    const refreshSelectedConversation = async () => {
      if (!selectedConversationId.value) return
      
      await loadSelectedConversation(selectedConversationId.value)
      
      // Reload in chat interface
      if (chatInterface.value) {
        chatInterface.value.handleConversationSelected(selectedConversationId.value)
      }
    }

    // Start a new conversation
    const startNewConversation = () => {
      selectedConversationId.value = 0 // Use 0 to indicate new conversation
      selectedConversation.value = null
    }

    // Format date for display
    const formatDate = (dateString: string): string => {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    onMounted(async () => {
      isLoading.value = true
      try {
        const response = await aiService.getUserConversations()
        if (response.status) {
          conversations.value = response.data.conversations.filter(c => c.is_active)
        }
      } catch (error) {
        console.error('Error loading conversations:', error)
      } finally {
        isLoading.value = false
      }
    })

    return {
      selectedConversationId,
      selectedConversation,
      chatInterface,
      isLoading,
      metrics,
      handleConversationSelected,
      deleteSelectedConversation,
      refreshSelectedConversation,
      startNewConversation,
      formatDate
    }
  }
})
</script>

<style scoped>
.conversation-history-view {
  padding: 1rem;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  background-color: var(--surface-ground);
}
</style>