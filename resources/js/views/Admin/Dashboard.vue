<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Total Users</span>
                        <div class="text-900 font-medium text-xl">{{ data.users }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-chart-line text-blue-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">24 new </span>
                <span class="text-500">since last visit</span> -->
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Pending Deposits</span>
                        <div class="text-900 font-medium text-xl">{{ data.pending_deposits }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-download text-orange-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium"></span>
                <!-- <span class="text-500">Profit earned</span> -->
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Pending Bank Withdrawals</span>
                        <div class="text-900 font-medium text-xl">{{  data.pending_bank }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-building text-cyan-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">520 </span>
                <span class="text-500">newly registered</span> -->
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Pending Wallet Withdrawals</span>
                        <div class="text-900 font-medium text-xl">{{  data.pending_wallet }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-bitcoin text-purple-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">85 </span>
                <span class="text-500">responded</span> -->
            </div>
        </div>

        <!-- <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Recent Transactions</h5>
                <DataTable :value="transactions.recent" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column style="width: 15%">
                        <template #header>ID</template>
                        <template #body="slotProps">
                            {{ slotProps.data.payment_id }}
                        </template>
                    </Column>
                    <Column field="name" header="Amount" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ formatCurrency(Number(slotProps.data.price_amount)) }}
                        </template>
                    </Column>
                    <Column field="price" header="Status" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            <InlineMessage :severity="statusBadge(slotProps.data.payment_status)">{{ slotProps.data.payment_status }}</InlineMessage>
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
            
            <div>
                <Card style="height: 40rem;">
                    <template #title>Asset Perfomance</template>
                    <template #subtitle>Realtime Portfolio Spread</template>
                    <template #content>
                        <CryptoHeatMap  style="height: 32rem;" class="relative h-350-px"
                        :options="{
                            width: '100%',
                            height: '100%'
                        }"
                        />
                    </template>
                </Card>
            </div>
            
        </div>

        <div class="col-12 xl:col-6">
            <div>
                <Card style="height: 40rem;">
                    <template #title>Market Trends</template>
                    <template #subtitle>Realtime market perfomance reports</template>
                    <template #content>
                        <MarketOverview style="height: 32rem;" class="relative h-350-px"
                        :options="{
                            theme: 'dark',
                            width: '100%',
                            height: '100%'
                        }"
                        />
                    </template>
                </Card>
            </div>

            <div class="card md:mt-4 sm:mt-4 xs:mt-3">
                <div class="flex align-items-center justify-content-between mb-4">
                    <h5>Notifications</h5>
                    <div>
                       
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>


<script lang="ts">
import { defineComponent, ref, watch, computed, reactive, onMounted, nextTick } from 'vue'
import { appLayout } from '@/lib/layout';
import { accountService } from '@/lib/accountService'
import type { Balances, ActiveTrading, Analytics } from '@/types/Account'
import type { Transaction } from '@/types/payments'
import { Charts, MarketOverview, CryptoHeatMap } from '../../components/Widgets';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InlineMessage from 'primevue/inlinemessage';
import Card from 'primevue/card';
import Button from 'primevue/button';
import { formatCurrency } from '@/lib'
import { toast } from '@/lib/notifications'
import { useNetwork } from '@/lib/request'

export default defineComponent({
    name: 'Dashboard',
    setup() {
        const { isDarkTheme } = appLayout();
        const lineOptions = ref<any>(null);
        const service = useNetwork(true)

        const data = ref<Analytics>({
            users: 0,
            pending_deposits: 0,
            pending_bank: 0,
            pending_wallet: 0
        })
        const transactions = reactive({
            recent: ([])
        })

        const formatTime = (value: string) => {
            let date = new Date(value)
            return date.toLocaleString('en-US', {hour12: false})
        };

        onMounted(() => {
            nextTick(() => {
                loadBalances()
                loadRecent()
            })
        });
        // const trading_balance = ref<any>(0.00)
        const loadBalances = () => {
            service.fetch<Analytics, {}>('office/adminAnalytics', {}).then((response) => {
                data.value = response.data!
            }).catch((error) => {
                console.log(error)
                toast.error('Unable to load analytics, please refresh')
            })
            // service.balances().then((data: Balances) => {
            //     balances.value.trading_balance = data.trading_balance
            //     balances.value.active_trades = data.active_trades
            //     balances.value.commission = data.commission
            //     balances.value.wallet = data.wallet
            //     return loadRecent()
            
        }

        const loadRecent = () => {
            // service.recentTransactions().then((data) => {
            //     // console.log("data: " + data)
            //     // @ts-ignore
            //     transactions.recent = data!
            // }).catch((error) => {
            //     notify({
            //         title: 'Error',
            //         text: 'Unable to recent transactions, please refresh',
            //         type: 'info'
            //     })
            //     // return loadRecent()
            // })
        }
        
        const applyDarkTheme = () => {
            lineOptions.value = {
                plugins: {
                    legend: {
                        labels: {
                            color: '#ebedef'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#ebedef'
                        },
                        grid: {
                            color: 'rgba(160, 167, 181, .3)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#ebedef'
                        },
                        grid: {
                            color: 'rgba(160, 167, 181, .3)'
                        }
                    }
                }
            };
        };
        const statusBadge = (status: string): string  => {
            switch(status) {
                case 'finished':
                    return 'success'
                case 'waiting':
                    return 'warn'
                case 'sending':
                case 'confirming':
                case 'confirmed':
                    return 'info'
                case 'refunded':
                    return 'contrast'
                case 'partially_paid':
                    return 'secondary'
                case 'failed':
                    return 'danger'
                default:
                    return 'danger'
            }
        }

        watch(isDarkTheme, (val) => {
                if (val) {
                    applyDarkTheme();
                } else {
                    // applyLightTheme();
                }
            },
            { immediate: true }
        );

        return { data, formatCurrency, transactions, statusBadge, formatTime }
        
    } ,
    components: {
        // Charts,
        // MarketOverview,
        // CryptoHeatMap,
        DataTable,
        Column,
        Card,
        Button
    }
})
</script>