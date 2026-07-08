<template>
    <div class="grid">
        <div class="col-12 lg:col-12 xl:col-9 md:col-12">
            <div class="card">
                <h5>Recent Withdrawal Requests</h5>
                <DataTable :value="transactions.recent" :rows="5" :paginator="true" responsiveLayout="scroll">
                    
                    <Column field="name" header="Amount" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ formatCurrency(Number(slotProps.data.amount)) }}
                        </template>
                    </Column>
                    <Column field="price" header="Status" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            <InlineMessage :severity="statusBadge(slotProps.data.status)">{{ slotProps.data.status }}</InlineMessage>
                        </template>
                    </Column>
                    <Column field="price" header="Date" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{  formatTime(slotProps.data.created_at) }}
                        </template>
                    </Column>
                    <Column style="width: 15%">
                        <template #header> View </template>
                        <template #body>
                            <Button icon="pi pi-search" type="button" class="p-button-text"></Button>
                        </template>
                    </Column>
                </DataTable>
              
            </div>
        </div>
    </div>


</template>


<script lang="ts">
import { defineComponent, ref, reactive, onMounted, nextTick } from 'vue'
import { formatCurrency } from '@/lib'
import { toast } from '@/lib/notifications'
import { useWithdrawal } from '@/lib/withdrawalService'
import type { WithdrawalRequest } from '../../types/Withdrawal'
import type { APIError } from '../../types/response'
export default defineComponent({
    setup() {
        const transactions = reactive({
            recent: ([])
        })

        const service = useWithdrawal()

        const loadRecent = () => {
            service.recentTransactions().then((data) => {
                console.log(data)
                // @ts-ignore
                transactions.recent = data!
            }).catch((error: APIError) => {
                toast.info('Error', error.message!)
            })
        }

        onMounted(() => {
            nextTick(() => {
                loadRecent()
            })
        });

        return { transactions }
    },
    methods: {
        statusBadge(status: string) {
            switch(status) {
                case 'pending':
                    return 'warn'
                case 'approved':
                    return 'success'
                case 'cancelled':
                    return 'danger'
                default:
                    return 'warn'
            }
        },
        formatTime(value: string) {
            let date = new Date(value)
            return date.toLocaleString('en-US', {hour12: false})
        },
        formatCurrency
    }
})
</script>