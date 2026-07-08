<template>
    <div class="investment-advisor p-4 border-1 border-gray-200 rounded-lg">
        <h3 class="text-xl font-semibold mb-3">Investment Assistant</h3>
        
        <div v-if="!showForm && !advice" class="text-center p-4">
            <i class="pi pi-chart-line text-5xl text-primary mb-3"></i>
            <h4 class="text-lg font-medium mb-2">Need investment advice?</h4>
            <p class="text-gray-600 mb-4">
                Get personalized investment recommendations based on your financial goals and risk tolerance.
            </p>
            <Button label="Get Advice" icon="pi pi-arrow-right" @click="showForm = true" />
        </div>
        
        <div v-if="showForm && !advice" class="investment-form">
            <form @submit.prevent="getAdvice">
                <div class="field mb-3">
                    <label for="risk_level" class="block font-medium mb-2">Risk Tolerance</label>
                    <div class="p-inputgroup">
                        <span class="p-inputgroup-addon">
                            <i class="pi pi-shield"></i>
                        </span>
                        <Dropdown
                            id="risk_level"
                            v-model="formData.risk_level"
                            :options="riskLevels"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Risk Level"
                            class="w-full"
                            :class="{'p-invalid': errors.risk_level}"
                        />
                    </div>
                    <small v-if="errors.risk_level" class="p-error">{{ errors.risk_level }}</small>
                </div>
                
                <div class="field mb-3">
                    <label for="investment_horizon" class="block font-medium mb-2">Investment Timeframe</label>
                    <div class="p-inputgroup">
                        <span class="p-inputgroup-addon">
                            <i class="pi pi-calendar"></i>
                        </span>
                        <Dropdown
                            id="investment_horizon"
                            v-model="formData.investment_horizon"
                            :options="horizons"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Timeframe"
                            class="w-full"
                            :class="{'p-invalid': errors.investment_horizon}"
                        />
                    </div>
                    <small v-if="errors.investment_horizon" class="p-error">{{ errors.investment_horizon }}</small>
                </div>
                
                <div class="field mb-3">
                    <label for="investment_amount" class="block font-medium mb-2">Investment Amount ($)</label>
                    <div class="p-inputgroup">
                        <span class="p-inputgroup-addon">$</span>
                        <InputNumber
                            id="investment_amount"
                            v-model="formData.investment_amount"
                            placeholder="Enter Amount"
                            :min="1"
                            :max="10000000"
                            class="w-full"
                            :class="{'p-invalid': errors.investment_amount}"
                        />
                    </div>
                    <small v-if="errors.investment_amount" class="p-error">{{ errors.investment_amount }}</small>
                </div>
                
                <div class="field mb-3">
                    <label for="goals" class="block font-medium mb-2">Financial Goals</label>
                    <Textarea
                        id="goals"
                        v-model="formData.goals"
                        placeholder="Describe your financial goals and what you hope to achieve with this investment..."
                        rows="3"
                        class="w-full"
                        :class="{'p-invalid': errors.goals}"
                    />
                    <small v-if="errors.goals" class="p-error">{{ errors.goals }}</small>
                </div>
                
                <div class="flex justify-content-end gap-2 mt-4">
                    <Button 
                        type="button" 
                        label="Cancel" 
                        class="p-button-outlined p-button-secondary" 
                        @click="showForm = false" 
                        :disabled="isLoading"
                    />
                    <Button 
                        type="submit" 
                        label="Get Advice" 
                        icon="pi pi-check" 
                        :loading="isLoading"
                    />
                </div>
            </form>
        </div>
        
        <div v-if="advice" class="advice-result">
            <div class="p-4 bg-gray-50 rounded mb-3">
                <h4 class="text-lg font-medium mb-2">Your Investment Profile:</h4>
                <ul class="list-none p-0 m-0">
                    <li class="mb-2">
                        <span class="font-medium">Risk Tolerance:</span> 
                        {{ getRiskLabel(formData.risk_level) }}
                    </li>
                    <li class="mb-2">
                        <span class="font-medium">Timeframe:</span> 
                        {{ getHorizonLabel(formData.investment_horizon) }}
                    </li>
                    <li class="mb-2">
                        <span class="font-medium">Investment Amount:</span> 
                        ${{ formData.investment_amount.toLocaleString() }}
                    </li>
                    <li>
                        <span class="font-medium">Goals:</span> 
                        {{ formData.goals }}
                    </li>
                </ul>
            </div>
            
            <h4 class="text-lg font-medium mb-2">Investment Recommendation:</h4>
            <div class="advice-content p-3 border-1 border-gray-200 rounded bg-white">
                <p class="whitespace-pre-wrap">{{ advice }}</p>
            </div>
            
            <div class="flex justify-content-end gap-2 mt-4">
                <Button 
                    label="Start New Analysis" 
                    icon="pi pi-refresh" 
                    @click="resetForm" 
                    class="p-button-outlined"
                />
                <Button 
                    label="Discuss With AI Assistant" 
                    icon="pi pi-comments" 
                    @click="openChat" 
                    class="p-button-primary"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, reactive } from 'vue'
import { useAIService, notify } from '../../lib'
import { useRouter } from 'vue-router'

export default defineComponent({
    name: 'InvestmentAdvisor',
    emits: ['open-chat'],
    setup(props, { emit }) {
        const router = useRouter()
        const aiService = useAIService()
        
        const showForm = ref(false)
        const isLoading = ref(false)
        const advice = ref('')
        
        const riskLevels = [
            { label: 'Low Risk', value: 'low' },
            { label: 'Medium Risk', value: 'medium' },
            { label: 'High Risk', value: 'high' }
        ]
        
        const horizons = [
            { label: 'Short Term (< 1 year)', value: 'short' },
            { label: 'Medium Term (1-5 years)', value: 'medium' },
            { label: 'Long Term (> 5 years)', value: 'long' }
        ]
        
        const formData = reactive({
            risk_level: '',
            investment_horizon: '',
            investment_amount: 0,
            goals: ''
        })
        
        const errors = reactive({
            risk_level: '',
            investment_horizon: '',
            investment_amount: '',
            goals: ''
        })
        
        const validateForm = () => {
            let isValid = true
            
            // Reset errors
            errors.risk_level = ''
            errors.investment_horizon = ''
            errors.investment_amount = ''
            errors.goals = ''
            
            if (!formData.risk_level) {
                errors.risk_level = 'Please select a risk level'
                isValid = false
            }
            
            if (!formData.investment_horizon) {
                errors.investment_horizon = 'Please select an investment timeframe'
                isValid = false
            }
            
            if (!formData.investment_amount || formData.investment_amount <= 0) {
                errors.investment_amount = 'Please enter a valid investment amount'
                isValid = false
            }
            
            if (!formData.goals.trim()) {
                errors.goals = 'Please describe your financial goals'
                isValid = false
            }
            
            return isValid
        }
        
        const getAdvice = async () => {
            if (!validateForm()) return
            
            isLoading.value = true
            
            try {
                const response = await aiService.getInvestmentAdvice(formData)
                
                if (response.status) {
                    advice.value = response.data.response
                } else {
                    notify({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to get investment advice. Please try again.'
                    })
                }
            } catch (error) {
                console.error('Investment advice error:', error)
                
                // Check if error is due to authentication
                const errorMessage = error?.message || '';
                if (errorMessage.includes('Unauthorized') || errorMessage.includes('401')) {
                    notify({
                        severity: 'error',
                        summary: 'Authentication Required',
                        detail: 'Please login to use the investment advisor.'
                    })
                } else {
                    notify({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to communicate with AI service. Please try again later.'
                    })
                }
            } finally {
                isLoading.value = false
            }
        }
        
        const resetForm = () => {
            formData.risk_level = ''
            formData.investment_horizon = ''
            formData.investment_amount = 0
            formData.goals = ''
            advice.value = ''
            showForm.value = true
        }
        
        const getRiskLabel = (value: string) => {
            const level = riskLevels.find(l => l.value === value)
            return level ? level.label : value
        }
        
        const getHorizonLabel = (value: string) => {
            const horizon = horizons.find(h => h.value === value)
            return horizon ? horizon.label : value
        }
        
        const openChat = () => {
            emit('open-chat', `I need help understanding this investment advice: ${advice.value.substring(0, 100)}...`)
        }
        
        return {
            showForm,
            isLoading,
            formData,
            errors,
            advice,
            riskLevels,
            horizons,
            getAdvice,
            resetForm,
            getRiskLabel,
            getHorizonLabel,
            openChat
        }
    }
})
</script>

<style scoped>
.investment-advisor {
    max-width: 100%;
}

.advice-content {
    max-height: 300px;
    overflow-y: auto;
}
</style>