<template>
    <div class="shadow-2 p-3 h-full flex flex-column surface-card" style="border-radius: 6px">
    <div class="text-900 font-medium text-xl mb-2">{{ plan.name }}</div>
    <div class="text-600">{{ plan.description }}</div>
    <hr class="my-3 mx-0 border-top-1 border-none surface-border" />
    <div class="flex align-items-center">
        <span class="font-bold text-2xl text-900">{{ Number(plan.return_percent).toFixed(0) }}% </span>
        <span class="ml-2 font-medium text-600">{{ $t('invest.totalReturns') }}</span>
    </div>
    <hr class="my-3 mx-0 border-top-1 border-none surface-border" />
    <ul class="list-none p-0 m-0 flex-grow-1">
        <li class="flex align-items-center mb-3">
            <i class="pi pi-check-circle text-green-500 mr-2"></i>
            <span>{{ formatCurrency(Number(plan.min_amount)) }} {{ $t('invest.minimumCapital') }}</span>
        </li>
        <li class="flex align-items-center mb-3">
            <i class="pi pi-check-circle text-green-500 mr-2"></i>
            <span>{{ $t('invest.termLabel', { term: plan.investment_term }) }}</span>
        </li>
        <template v-for="(item, i) in plan.details" :key="item">
            <li class="flex align-items-center mb-3">
                <i class="pi pi-check-circle text-green-500 mr-2"></i>
                <span>{{ item.feature }}</span>
            </li>
        </template>
    </ul>
    <hr v-if="showBuy!" class="mb-3 mx-0 border-top-1 border-none surface-border mt-auto" />
    <div v-if="showBuy!">
        <div v-if="authenticated!">

        </div>
        <div v-else>
            <router-link :to="{name: 'Register'}">
                <Button :label="$t('invest.getStarted')" class="p-3 w-full mt-auto"></Button>
            </router-link>
        </div>
    
    </div>
</div>
</template>

<script lang="ts">
import { defineComponent, watch, type PropType } from 'vue';
import type { InvestmentPlan, InvestmentPlanDetail } from '../../types/investments'
import { formatCurrency } from '@/lib'

export default defineComponent({
    props: {
        plan: {
            type: Object as PropType<InvestmentPlan>,
            required: true
        },
        showBuy: {
            default: false,
            type: Boolean,
            required: false
        },
        authenticated: {
            default: false,
            type: Boolean,
            required: false
        }
    },
    setup() {


        // watch(plan, (newVal) => {
        //     if(newVal.name !== undefined) {

        //     }
        // })
        return { formatCurrency }
    }
})
</script>
