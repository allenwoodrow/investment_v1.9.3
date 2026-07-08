<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">{{ $t('dashboard.tradingBalance') }}</span>
                        <div class="text-900 font-medium text-xl">{{ formatCurrency(balances.trading_balance) }}</div>
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
                        <span class="block text-500 font-medium mb-3">{{ $t('dashboard.activeInvestments') }}</span>
                        <div class="text-900 font-medium text-xl">{{ balances.active_trades!.count }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-map-marker text-orange-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">{{ formatCurrency(balances.active_trades!.net_gain) }}+ </span> -->
                <!-- <span class="text-500">Profit earned</span> -->
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">{{ $t('dashboard.walletBalance') }}</span>
                        <div class="text-900 font-medium text-xl">{{ formatCurrency(balances.wallet ?? 0.00) }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-inbox text-cyan-500 text-xl"></i>
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
                        <span class="block text-500 font-medium mb-3">{{ $t('dashboard.commission') }}</span>
                        <div class="text-900 font-medium text-xl">{{  formatCurrency(balances.commission) }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-users text-purple-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">85 </span>
                <span class="text-500">responded</span> -->
            </div>
        </div>

        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>{{ $t('app.dashboard.recentTransactions') }}</h5>
                <DataTable :value="transactions.recent" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column style="width: 15%">
                        <template #header>{{ $t('common.id') }}</template>
                        <template #body="slotProps">
                            {{ slotProps.data.payment_id }}
                            <!-- <img :src="'demo/images/product/' + slotProps.data.image" :alt="slotProps.data.image" width="50" class="shadow-2" /> -->
                        </template>
                    </Column>
                    <Column field="name" :header="$t('common.amount')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ formatCurrency(Number(slotProps.data.price_amount)) }}
                        </template>
                    </Column>
                    <Column field="price" :header="$t('common.status')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            <InlineMessage :severity="statusBadge(slotProps.data.payment_status)">{{ slotProps.data.payment_status }}</InlineMessage>
                        </template>
                    </Column>
                    <Column field="price" :header="$t('common.date')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{  formatTime(slotProps.data.created_at) }}
                        </template>
                    </Column>
                    <Column style="width: 15%">
                        <template #header>{{ $t('common.view') }}</template>
                        <template #body>
                            <Button icon="pi pi-search" type="button" class="p-button-text"></Button>
                        </template>
                    </Column>
                </DataTable>
              
            </div>
            
            <div>
                <Card style="height: 40rem;">
                    <template #title>{{ $t('app.dashboard.assetPerformance') }}</template>
                    <template #subtitle>{{ $t('app.dashboard.realtimePortfolioSpread') }}</template>
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
                    <template #title>{{ $t('app.dashboard.marketTrends') }}</template>
                    <template #subtitle>{{ $t('app.dashboard.realtimeMarketPerformanceReports') }}</template>
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
                    <h5>{{ $t('app.dashboard.notifications') }}</h5>
                    <div>
                        <!-- <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded" @click="$refs.menu1.toggle($event)"></Button> -->
                        <!-- <Menu ref="menu1" :popup="true" :model="items"></Menu> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { defineComponent, ref, watch, computed, reactive, onMounted, nextTick } from 'vue'
import { appLayout } from '@/lib/layout';
import { accountService } from '@/lib/accountService'
import type { Balances, ActiveTrading } from '../../types/Account'
import type { Transaction } from '../../types/payments'
import { Charts, MarketOverview, CryptoHeatMap } from '../../components/Widgets';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InlineMessage from 'primevue/inlinemessage';
import Card from 'primevue/card';
import Button from 'primevue/button';
import { formatCurrency } from '@/lib'
import { toast } from '@/lib/notifications'

export default defineComponent({
    name: 'Dashboard',
    setup() {
        const { isDarkTheme } = appLayout();
        const products = ref(null);
        const lineOptions = ref<any>(null);
        const service = accountService()

        const balances = ref<Balances>({
            trading_balance: 0.00,
            active_trades: {
                count: 0,
                net_gain: 0.00
            },
            commission: 0,
            wallet: 0
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
            service.balances().then((data: Balances) => {
                balances.value.trading_balance = data.trading_balance
                balances.value.active_trades = data.active_trades
                balances.value.commission = data.commission
                balances.value.wallet = data.wallet
                return loadRecent()
            }).catch((error) => {
                toast.error('Unable to load balances, please try again later')
            })
        }

        const loadRecent = () => {
            service.recentTransactions().then((data) => {
                // console.log("data: " + data)
                // @ts-ignore
                transactions.recent = data!
            }).catch((error) => {
                toast.info('Error', 'Unable to recent transactions, please refresh')
                // return loadRecent()
            })
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

        return { balances, formatCurrency, transactions, statusBadge, formatTime }
        
    } ,
    components: {
        Charts,
        MarketOverview,
        CryptoHeatMap,
        DataTable,
        Column,
        Card,
        Button
    }
})
</script>