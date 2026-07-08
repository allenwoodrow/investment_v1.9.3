<template>
  <div class="ai-demo">
    <PageHeader title="AI Investment Assistant" description="Get personalized investment advice and chat with our AI assistant" />
    
    <div class="grid">
      <div class="col-12 md:col-6 lg:col-4 mb-4">
        <InvestmentAdvisor @open-chat="handleOpenChat" />
      </div>
      
      <div class="col-12 md:col-6 lg:col-8">
        <ChatInterface :initialMessage="initialMessage" />
      </div>
    </div>

    <div class="mt-6">
      <h3 class="text-xl font-semibold mb-3">How to Use the AI Assistant</h3>
      
      <div class="grid">
        <div class="col-12 md:col-4 mb-4">
          <Card class="h-full">
            <template #title>Investment Advice</template>
            <template #content>
              <p>Fill out the investment form with your risk tolerance, investment timeframe, amount, and financial goals to get personalized investment recommendations.</p>
            </template>
          </Card>
        </div>
        
        <div class="col-12 md:col-4 mb-4">
          <Card class="h-full">
            <template #title>Ask About Markets</template>
            <template #content>
              <p>Ask specific questions about stocks, market trends, investment strategies, or financial instruments to get detailed information.</p>
            </template>
          </Card>
        </div>
        
        <div class="col-12 md:col-4 mb-4">
          <Card class="h-full">
            <template #title>Limitations</template>
            <template #content>
              <p>The AI assistant is focused exclusively on investment-related topics. For personalized financial advice, please consult with a certified financial advisor.</p>
            </template>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { PageHeader, ChatInterface, InvestmentAdvisor } from '../components'

export default defineComponent({
  name: 'AIDemo',
  components: {
    PageHeader,
    ChatInterface,
    InvestmentAdvisor
  },
  setup() {
    const initialMessage = ref('')
    
    const handleOpenChat = (message: string) => {
      initialMessage.value = message
    }
    
    return {
      initialMessage,
      handleOpenChat
    }
  }
})
</script>

<style scoped>
.ai-demo {
  padding: 1.5rem;
}
</style>