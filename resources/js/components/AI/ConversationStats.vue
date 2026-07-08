<template>
  <div class="conversation-stats">
    <div class="header p-3 flex justify-between items-center border-bottom-1 border-gray-200">
      <h3 class="text-lg font-semibold mb-0">AI Analytics</h3>
      <Button
        icon="pi pi-refresh"
        class="p-button-text p-button-sm"
        @click="loadStats"
        :loading="isLoading"
        tooltip="Refresh"
        tooltipOptions="top"
      />
    </div>

    <div class="p-3" v-if="isLoading">
      <ProgressSpinner style="width: 50px" strokeWidth="4" fill="var(--surface-ground)" />
      <p class="text-center text-gray-500">Loading analytics...</p>
    </div>

    <div class="content p-3" v-else>
      <!-- Summary Cards -->
      <div class="grid mb-4">
        <div class="col-12 md:col-4">
          <div class="p-3 border-1 border-gray-200 rounded bg-blue-50">
            <div class="flex justify-between items-center">
              <span class="text-blue-800 font-medium">Total Conversations</span>
              <i class="pi pi-comments text-blue-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-blue-900 mt-2">{{ stats.totalConversations }}</div>
          </div>
        </div>
        
        <div class="col-12 md:col-4">
          <div class="p-3 border-1 border-gray-200 rounded bg-green-50">
            <div class="flex justify-between items-center">
              <span class="text-green-800 font-medium">Total Messages</span>
              <i class="pi pi-envelope text-green-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-green-900 mt-2">{{ stats.totalMessages }}</div>
          </div>
        </div>
        
        <div class="col-12 md:col-4">
          <div class="p-3 border-1 border-gray-200 rounded bg-purple-50">
            <div class="flex justify-between items-center">
              <span class="text-purple-800 font-medium">Avg. Response Time</span>
              <i class="pi pi-clock text-purple-600 text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-purple-900 mt-2">{{ stats.avgResponseTime }}s</div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid">
        <div class="col-12 lg:col-6 mb-4">
          <div class="border-1 border-gray-200 rounded p-3">
            <h4 class="text-lg font-medium mb-3">Messages by Role</h4>
            <Chart type="pie" :data="messagesByRoleData" :options="pieOptions" class="h-20rem" />
          </div>
        </div>
        
        <div class="col-12 lg:col-6 mb-4">
          <div class="border-1 border-gray-200 rounded p-3">
            <h4 class="text-lg font-medium mb-3">Conversations Over Time</h4>
            <Chart type="line" :data="conversationsTimeData" :options="lineOptions" class="h-20rem" />
          </div>
        </div>
      </div>

      <!-- Topics Table -->
      <div class="border-1 border-gray-200 rounded p-3 mb-4">
        <h4 class="text-lg font-medium mb-3">Popular Topics</h4>
        <DataTable :value="stats.topTopics" responsiveLayout="scroll" class="p-datatable-sm">
          <Column field="topic" header="Topic"></Column>
          <Column field="count" header="Count">
            <template #body="{data}">
              <div class="flex align-items-center">
                <div class="w-8rem bg-gray-200 border-round mr-3" style="height: 0.5rem">
                  <div class="h-full bg-primary border-round" :style="{ width: (data.count / stats.topTopics[0].count * 100) + '%' }"></div>
                </div>
                <span>{{ data.count }}</span>
              </div>
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, onMounted } from 'vue'
import { useAIService } from '../../lib'

interface StatData {
  totalConversations: number;
  totalMessages: number;
  avgResponseTime: number;
  messagesByRole: {
    user: number;
    assistant: number;
    system: number;
  };
  conversationsByDate: Record<string, number>;
  topTopics: Array<{
    topic: string;
    count: number;
  }>;
}

export default defineComponent({
  name: 'ConversationStats',
  setup() {
    const isLoading = ref(false)
    const aiService = useAIService()
    
    // Sample data for demonstration - this would be populated from the API in a real scenario
    const stats = reactive<StatData>({
      totalConversations: 0,
      totalMessages: 0,
      avgResponseTime: 0,
      messagesByRole: {
        user: 0,
        assistant: 0,
        system: 0
      },
      conversationsByDate: {},
      topTopics: []
    })
    
    // Chart data
    const messagesByRoleData = ref({
      labels: ['User', 'Assistant', 'System'],
      datasets: [
        {
          data: [0, 0, 0],
          backgroundColor: ['#2196F3', '#4CAF50', '#FFC107'],
          hoverBackgroundColor: ['#1E88E5', '#43A047', '#FFB300']
        }
      ]
    })
    
    const conversationsTimeData = ref({
      labels: [],
      datasets: [
        {
          label: 'Conversations',
          data: [],
          fill: false,
          borderColor: '#4CAF50',
          tension: 0.4
        },
        {
          label: 'Messages',
          data: [],
          fill: false,
          borderColor: '#2196F3',
          tension: 0.4
        }
      ]
    })
    
    // Chart options
    const pieOptions = {
      plugins: {
        legend: {
          position: 'bottom'
        }
      },
      responsive: true,
      maintainAspectRatio: false
    }
    
    const lineOptions = {
      plugins: {
        legend: {
          position: 'top'
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#495057'
          },
          grid: {
            color: '#ebedef'
          }
        },
        y: {
          ticks: {
            color: '#495057'
          },
          grid: {
            color: '#ebedef'
          },
          beginAtZero: true
        }
      },
      responsive: true,
      maintainAspectRatio: false
    }
    
    const loadStats = async () => {
      isLoading.value = true
      
      try {
        // In a real implementation, this would be an API call
        // For demonstration, we'll simulate data
        
        // Get conversations
        const response = await aiService.getUserConversations()
        
        if (response.status) {
          const conversations = response.data.conversations
          
          // Calculate stats
          stats.totalConversations = conversations.length
          stats.totalMessages = conversations.reduce((sum, conv) => sum + conv.message_count, 0)
          stats.avgResponseTime = 1.2 // This would come from actual timing data
          
          // Fetch conversation message counts by role from backend
          // For demo, using placeholder data
          stats.messagesByRole = {
            user: Math.floor(stats.totalMessages / 2),
            assistant: Math.floor(stats.totalMessages / 2) - 5,
            system: 5
          }
          
          // Update pie chart data
          messagesByRoleData.value.datasets[0].data = [
            stats.messagesByRole.user,
            stats.messagesByRole.assistant,
            stats.messagesByRole.system
          ]
          
          // Generate dates for last 7 days
          const dates = []
          const conversationData = []
          const messageData = []
          
          const today = new Date()
          for (let i = 6; i >= 0; i--) {
            const date = new Date(today)
            date.setDate(date.getDate() - i)
            const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            
            dates.push(dateStr)
            
            // Random data for demonstration
            const convsForDay = i === 0 ? stats.totalConversations : Math.floor(Math.random() * 5)
            const msgsForDay = i === 0 ? stats.totalMessages : convsForDay * Math.floor(Math.random() * 10 + 5)
            
            conversationData.push(convsForDay)
            messageData.push(msgsForDay)
          }
          
          // Update line chart data
          conversationsTimeData.value.labels = dates
          conversationsTimeData.value.datasets[0].data = conversationData
          conversationsTimeData.value.datasets[1].data = messageData
          
          // Set top topics (placeholder data)
          stats.topTopics = [
            { topic: 'Stock Investment', count: 12 },
            { topic: 'Market Analysis', count: 8 },
            { topic: 'Retirement Planning', count: 6 },
            { topic: 'Portfolio Diversity', count: 5 },
            { topic: 'Risk Management', count: 3 }
          ]
        }
      } catch (error) {
        console.error('Error loading stats:', error)
      } finally {
        isLoading.value = false
      }
    }
    
    onMounted(() => {
      loadStats()
    })
    
    return {
      isLoading,
      stats,
      messagesByRoleData,
      conversationsTimeData,
      pieOptions,
      lineOptions,
      loadStats
    }
  }
})
</script>

<style scoped>
.conversation-stats {
  height: 100%;
  display: flex;
  flex-direction: column;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.content {
  flex: 1;
  overflow-y: auto;
}
</style>