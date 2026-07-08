<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <h5>{{ $t('app.investHistory.investmentHistory') }}</h5>
                <DataTable :value="investments" :rows="15" :paginator="true" responsiveLayout="scroll">
                    <Column field="name" :header="$t('app.investHistory.accountType')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.plan.name }}
                        </template>
                    </Column>

                    <Column field="name" :header="$t('app.investHistory.investmentTerm')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ slotProps.data.plan.investment_term }}
                        </template>
                    </Column>
                    <Column field="price" :header="$t('common.amount')" :sortable="true">
                        <template #body="slotProps">
                            {{ formatCurrency(Number(slotProps.data.amount)) }}
                        </template>
                    </Column>
                    <Column field="price" :header="$t('app.investHistory.totalProfit')" :sortable="true">
                        <template #body="slotProps">
                            {{ formatCurrency(Number(slotProps.data.totalProfit)) }}
                        </template>
                    </Column>

                    <Column field="price" :header="$t('app.investHistory.maturityDate')" :sortable="true">
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.maturity) }}
                        </template>
                    </Column>

                    <Column style="width: 25%">
                        <template #header>{{ $t('common.status') }}</template>
                        <template #body="slotProps">
                            <InlineMessage :severity="statusBadge(slotProps.data.status)">{{ slotProps.data.status }}</InlineMessage>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { formatCurrency } from '@/lib';
import type { Investment } from '../../types/investments';
import { investmentService } from '@/lib/investmentService'

export default defineComponent({
    setup() {
        const investments = ref<Investment[]>([])

        const service = investmentService()
        onMounted(() => {
            service.getInvestments().then((plans) => {
                investments.value = plans
            }).catch(error => console.log(error))
        })

        return {investments}
    },
    components: {
        DataTable, Column, ColumnGroup, Row
    },
    methods: {
        formatCurrency,
        statusBadge: (status: string): string  => {
            switch(status) {
                case 'pending':
                    return 'warn'
                case 'complete':
                    return 'success'
                case 'cancelled':
                    return 'error'
                case 'suspended':
                    return 'secondary'
                case 'running':
                    return 'info'
                default:
                    return 'warning'
            }
        },
        formatDate: (date: string): string | undefined => {
            const obj = new Date(date)
            return date.toLocaleString()
        }
    }
})
</script>