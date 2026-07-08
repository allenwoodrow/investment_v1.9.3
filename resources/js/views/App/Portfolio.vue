<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Panel :header="$t('app.portfolio.overview')">
                    <p class="m-0">
                        Our smart trading systems hedge and minimize risk based on market performance in realtime to ensure you are guaranteed return of investment as well as return on investment at all times.
                    </p>
                </Panel>
            </div>
        </div>
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
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">{{ $t('dashboard.activeInvestments') }}</span>
                        <div class="text-900 font-medium text-xl">{{ active }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-green-100 border-round" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-file text-green-500 text-xl"></i>
                    </div>
                </div>
                <!-- <span class="text-green-500 font-medium">24 new </span>
                <span class="text-500">since last visit</span> -->
            </div>
        </div>
        
    </div>

    <div class="grid">
        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>{{ $t('app.portfolio.profitMetrics') }}</h5>
                <Chart type="bar" :data="profitMetrics" :options="metricOptions" class="h-30rem" />
                
            </div>
        </div>

        <div class="col-12 xl:col-6 lg:col-6">
            <div class="card ">
                <h5>{{ $t('app.portfolio.realtimeAssetDistribution') }}</h5>
                <div class="flex justify-content-center">
                    <Chart type="doughnut" :data="distribution" :options="chartOptions" class="w-full md:w-30rem" />
                </div>
                
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, reactive, onMounted } from 'vue'
import type { Balances } from '@/types/Account'
import { formatCurrency } from '@/lib'
import { toast } from '@/lib/notifications'
import { accountService } from '@/lib/accountService'
import Panel from 'primevue/panel';
import type { APIError } from '@/types/response';
import { investmentService } from '@/lib/investmentService'

export default defineComponent({
    setup() {
        const balances = reactive<Balances>({
            trading_balance: 0.00,
            active_trades: {
                count: 0,
                net_gain: 0.00
            },
            commission: 0,
            wallet: 0
        })
        const distribution = ref();
        const profitMetrics = ref();
        const chartOptions = ref<object>({});
        const metricOptions = ref<object>({});
        const active = ref<number>(0)
        const service = investmentService()
        const account = accountService()
        onMounted((() => {
            chartOptions.value = setChartOptions();
            distribution.value = setDistributionData()
            metricOptions.value = setMetricOptions()
            account.balances().then((data: Balances) => {
                balances.trading_balance = data.trading_balance
                balances.active_trades = data.active_trades
                balances.commission = data.commission
                balances.wallet = data.wallet
                return
            }).catch((error: APIError) => {
                toast.info('Error', error.message!)
            })

            service.getMetrics().then((metrics) => {
                profitMetrics.value = metrics
            }).catch(error => {
                console.log(error)
            })
        }))

        const random = (min: number, max:number) => {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        const setDistributionData = () => {
            const documentStyle = getComputedStyle(document.body)
            return {
                labels: ['Crypto', 'Gold', 'Real Estate', 'Indices', 'Stocks'],
                datasets: [
                    {
                        data: [random(20, 50), random(10, 40), random(10, 40), random(10, 35), random(10, 100)],
                        backgroundColor: [documentStyle.getPropertyValue('--cyan-500'), documentStyle.getPropertyValue('--orange-500'), documentStyle.getPropertyValue('--gray-500'), documentStyle.getPropertyValue('--green-500'), documentStyle.getPropertyValue('--blue-500')],
                        hoverBackgroundColor: [documentStyle.getPropertyValue('--cyan-400'), documentStyle.getPropertyValue('--orange-400'), documentStyle.getPropertyValue('--gray-400')]
                    }
                ]
            };
        };

        const setChartOptions = () => {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--text-color');

            return {
                plugins: {
                    legend: {
                        labels: {
                            cutout: '60%',
                            color: textColor
                        }
                    }
                }
            };
        };

        const setMetricOptions = () => {
            const documentStyle = getComputedStyle(document.documentElement);
            const textColor = documentStyle.getPropertyValue('--text-color');
            const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
            const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

            return {
                maintainAspectRatio: false,
                aspectRatio: 0.6,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder
                        }
                    },
                    y: {
                        ticks: {
                            color: textColorSecondary
                        },
                        grid: {
                            color: surfaceBorder
                        }
                    }
                }
            };
        }

        return { balances, active, chartOptions, distribution, profitMetrics, metricOptions }
    },
    methods: {
        formatCurrency
    },
    components: {
        Panel
    }
})
</script>