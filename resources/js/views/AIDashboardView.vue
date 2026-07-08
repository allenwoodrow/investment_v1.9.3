<template>
  <div class="ai-dashboard">
    <PageHeader 
      title="AI Investment Assistant Dashboard" 
      description="Analyze stocks, get investment advice, and explore cryptocurrencies"
    />

    <div class="grid">
      <!-- Main Chat Area -->
      <div class="col-12 lg:col-8 mb-4">
        <TabView>
          <TabPanel header="General Assistant">
            <div class="p-3">
              <h3 class="text-xl mb-3">Investment AI Assistant</h3>
              <p class="mb-4 text-gray-600">
                Ask questions about stocks, market trends, investment strategies, and financial planning.
                Our AI assistant is specialized in financial topics.
              </p>
              <ChatInterface />
            </div>
          </TabPanel>
          
          <TabPanel header="Investment Advisor">
            <div class="p-3">
              <h3 class="text-xl mb-3">Personalized Investment Advice</h3>
              <p class="mb-4 text-gray-600">
                Get personalized investment recommendations based on your risk tolerance,
                investment horizon, and financial goals.
              </p>
              <InvestmentAdvisor />
            </div>
          </TabPanel>
          
          <TabPanel header="Market Analysis">
            <div class="p-3">
              <h3 class="text-xl mb-3">Market Analysis</h3>
              <p class="mb-4 text-gray-600">
                Get AI-generated analysis of specific stocks, sectors, or market trends.
                Enter a stock symbol or market sector to analyze.
              </p>
              
              <div class="market-analysis p-3 border-1 surface-border rounded mb-4">
                <div class="flex mb-3">
                  <InputText 
                    v-model="marketQuery" 
                    placeholder="Enter stock symbol or sector (e.g., AAPL, Tech Sector)" 
                    class="flex-1 mr-2" 
                  />
                  <Button 
                    label="Analyze" 
                    icon="pi pi-search"
                    @click="getMarketAnalysis"
                    :loading="isAnalyzing"
                  />
                </div>
                
                <div v-if="analysisResult" class="analysis-result p-3 bg-gray-50 border-round">
                  <h4 class="text-lg mb-2">Analysis: {{ marketQuery }}</h4>
                  <p style="white-space: pre-line">{{ analysisResult }}</p>
                </div>
              </div>
            </div>
          </TabPanel>
        </TabView>
      </div>
      
      <!-- Sidebar / Info -->
      <div class="col-12 lg:col-4">
        <div class="border-1 surface-border rounded p-3 mb-4">
          <h3 class="text-xl mb-3">Investment AI Features</h3>
          
          <div class="features">
            <div class="feature-item mb-3 p-3 border-1 border-round surface-ground">
              <i class="pi pi-comments text-2xl text-primary mb-2"></i>
              <h4 class="text-lg mb-1">Investment Assistant</h4>
              <p class="text-sm text-gray-600">Ask questions about any financial topic and get expert insights.</p>
            </div>
            
            <div class="feature-item mb-3 p-3 border-1 border-round surface-ground">
              <i class="pi pi-chart-line text-2xl text-primary mb-2"></i>
              <h4 class="text-lg mb-1">Market Analysis</h4>
              <p class="text-sm text-gray-600">Get detailed analysis of stocks, cryptocurrencies, and market sectors.</p>
            </div>
            
            <div class="feature-item mb-3 p-3 border-1 border-round surface-ground">
              <i class="pi pi-briefcase text-2xl text-primary mb-2"></i>
              <h4 class="text-lg mb-1">Investment Advisor</h4>
              <p class="text-sm text-gray-600">Receive personalized investment strategies based on your goals.</p>
            </div>
          </div>
          
          <div class="disclaimer p-3 bg-blue-50 border-round mt-4">
            <h4 class="text-md text-blue-900 mb-2 font-medium">Important Disclaimer</h4>
            <p class="text-sm text-blue-800">
              All investment information provided is for educational purposes only and should not be considered financial advice.
              Always consult with a certified financial advisor before making investment decisions.
            </p>
          </div>
        </div>
        
        <!-- Connection Diagnostic -->
        <AIConnectionDiagnostic v-if="isAdmin" />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from 'vue'
import { useAIService, notify } from '../lib'
import { useAuth } from '../lib/auth'
import AIConnectionDiagnostic from '../components/AI/AIConnectionDiagnostic.vue'

export default defineComponent({
  name: 'AIDashboardView',
  components: {
    AIConnectionDiagnostic
  },
  setup() {
    const aiService = useAIService()
    const auth = useAuth()
    
    const marketQuery = ref('')
    const analysisResult = ref('')
    const isAnalyzing = ref(false)
    
    // Only show diagnostics to admins
    const isAdmin = computed(() => {
      return auth.user?.email?.includes('admin') || false
    })
    
    // Get market analysis
    const getMarketAnalysis = async () => {
      if (!marketQuery.value.trim()) {
        notify({
          severity: 'warn',
          summary: 'Warning',
          detail: 'Please enter a stock symbol or sector to analyze'
        })
        return
      }
      
      isAnalyzing.value = true
      
      try {
        const response = await aiService.getMarketAnalysis(marketQuery.value)
        
        if (response.status) {
          analysisResult.value = response.data.response
        } else {
          notify({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to get market analysis. Please try again.'
          })
        }
      } catch (error) {
        console.error('Market analysis error:', error)
        notify({
          severity: 'error',
          summary: 'Error',
          detail: 'An error occurred while getting market analysis'
        })
      } finally {
        isAnalyzing.value = false
      }
    }
    
    return {
      marketQuery,
      analysisResult,
      isAnalyzing,
      getMarketAnalysis,
      isAdmin
    }
  }
})
</script>

<style scoped>
.ai-dashboard {
  padding: 1rem;
}
</style>