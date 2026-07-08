<template>
  <div class="ai-connection-diagnostic p-3 border-1 surface-border rounded">
    <div class="flex justify-content-between align-items-center mb-3">
      <h3 class="text-lg font-medium m-0">LocalAI Connection Status</h3>
      <Button 
        label="Check Connection" 
        icon="pi pi-sync" 
        class="p-button-sm"
        @click="checkConnection"
        :loading="isChecking"
      />
    </div>
    
    <div v-if="isChecking" class="flex justify-content-center p-3">
      <ProgressSpinner style="width: 50px" strokeWidth="4" />
      <span class="ml-3 text-gray-500">Testing connection to LocalAI server...</span>
    </div>
    
    <div v-else-if="!connectionStatus" class="p-3 text-center">
      <div class="mb-3 text-gray-500">
        <i class="pi pi-server text-3xl"></i>
        <p>Click the button above to check the LocalAI server connection.</p>
      </div>
    </div>
    
    <div v-else-if="connectionStatus.status" class="connection-success p-3 bg-green-50 border-round">
      <div class="flex align-items-center mb-3">
        <i class="pi pi-check-circle text-green-500 text-2xl mr-2"></i>
        <span class="font-medium text-green-700">Connection Successful</span>
      </div>
      <p class="text-green-700 mb-2">Successfully connected to LocalAI server at: <code>{{ connectionStatus.data.server_url }}</code></p>
      
      <div class="model-status p-2 mb-2 bg-white border-round">
        <div class="flex align-items-center">
          <i :class="connectionStatus.data.model_exists ? 'pi pi-check text-green-500' : 'pi pi-times text-red-500'" class="mr-2"></i>
          <div>
            <p class="m-0 font-medium">Model: {{ connectionStatus.data.configured_model }}</p>
            <p v-if="connectionStatus.data.model_exists" class="m-0 text-sm text-green-600">Model available and ready</p>
            <p v-else class="m-0 text-sm text-red-600">
              Model not found on server. Available models: {{ connectionStatus.data.available_models.join(', ') }}
            </p>
          </div>
        </div>
      </div>
      
      <div class="performance p-2 bg-white border-round">
        <p class="m-0 text-sm">
          <span class="font-medium">Response time:</span> {{ Math.round(connectionStatus.data.response_time_ms) }}ms
        </p>
      </div>
    </div>
    
    <div v-else class="connection-error p-3 bg-red-50 border-round">
      <div class="flex align-items-center mb-3">
        <i class="pi pi-exclamation-triangle text-red-500 text-2xl mr-2"></i>
        <span class="font-medium text-red-700">Connection Failed</span>
      </div>
      <p class="text-red-700 mb-2">Failed to connect to LocalAI server at: <code>{{ connectionStatus.data.server_url }}</code></p>
      <p class="text-red-600">Error: {{ connectionStatus.message || connectionStatus.data.error }}</p>
      
      <div class="help-section p-3 mt-3 bg-white border-round">
        <h4 class="text-md font-medium mb-2">Troubleshooting Steps:</h4>
        <ul class="m-0 p-0 ml-3 text-sm">
          <li class="mb-1">Verify that the LocalAI server is running at the configured URL</li>
          <li class="mb-1">Check that the server allows connections from your IP address</li>
          <li class="mb-1">Ensure the AI model specified in configuration is loaded on the server</li>
          <li class="mb-1">Check network connectivity between your application and the LocalAI server</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { useAIService } from '../../lib'
import type { APIResponse } from '../../types/ai'

export default defineComponent({
  name: 'AIConnectionDiagnostic',
  setup() {
    const aiService = useAIService()
    const isChecking = ref(false)
    const connectionStatus = ref<APIResponse<any> | null>(null)
    
    const checkConnection = async () => {
      isChecking.value = true
      try {
        const response = await aiService.testConnection()
        connectionStatus.value = response
      } catch (error) {
        console.error('Failed to check connection:', error)
        connectionStatus.value = {
          status: false,
          message: 'Failed to check connection',
          data: {
            error: error?.message || 'Unknown error',
            server_url: 'Unknown'
          }
        }
      } finally {
        isChecking.value = false
      }
    }
    
    return {
      isChecking,
      connectionStatus,
      checkConnection
    }
  }
})
</script>

<style scoped>
.ai-connection-diagnostic {
  max-width: 100%;
}

code {
  background-color: rgba(0,0,0,0.05);
  padding: 2px 4px;
  border-radius: 4px;
  font-family: monospace;
}
</style>