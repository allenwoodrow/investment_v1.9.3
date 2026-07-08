<template>
    <div class="card">
            <DataTable v-model:filters="filters" :value="transactions" paginator :rows="25" :rowsPerPageOptions="[25, 50, 100]" showGridlines dataKey="id" filterDisplay="menu" :loading="isLoading" :globalFilterFields="['payment_id']">
                <template #header>
                    <div class="flex justify-content-between">
                        <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="initFilters()" />
                        <IconField iconPosition="left">
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                        </IconField>
                    </div>
                </template>
                <template #empty> No transactions found. </template>
                <template #loading> Loading customers data. Please wait. </template>
                <Column field="amount" header="Amount" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ formatCurrency(Number(data.amount)) }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by Amount" />
                    </template>
                </Column>
    
                <Column field="user.username" header="Username" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ data.user?.username ?? 'Unknown user' }}
                        <!-- {{ formatCurrency(Number(data.price_amount)) }} -->
                    </template>
                </Column>
    
                <Column field="bank.bank_name" header="Bank Info" style="min-width: 12rem">
                    <template #body="{ data }">
                        <Button label="View" icon="pi pi-eye" severity="info" @click="showBankInfo(data)"></Button>
                        <!-- {{ Number(data.pay_amount).toFixed(2) ?? 0.00 }} {{ data.pay_currency }} -->
                    </template>
                </Column>
    
                <Column field="status" header="Status" style="min-width: 12rem">
                    <template #body="{ data }">
                        <Tag :value="data.status" :severity="getSeverity(data.status)" />
                    </template>
                </Column>
    
                <Column field="status" header="Actions" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                    <template #body="{ data }">
                        <Button v-if="data.status == 'pending'" type="button" icon="pi pi-times" @click="toggleWithdrawal(data.id, 'reject')" severity="danger" label="Reject"></Button>
                        <Button v-if="data.status == 'pending'" type="button" icon="pi pi-check" @click="toggleWithdrawal(data.id, 'approve')" severity="success" label="Approve"></Button>
                        <!-- <Button v-if="data.approved_at === NULL && data.approved == NULL" type="button" icon="pi pi-check" @click="toggleWithdrawal(data.id, 'approve')" severity="success" label="Approve"></Button>
                        
                        <Button v-if="data.status == 'running'" type="button" icon="pi pi-upload" @click="showBankInfo(data.id)" severity="info" label="Pay Profit"></Button>
                        <Button v-if="data.status == 'running'" type="button" icon="pi pi-stop" @click="toggleWithdrawal(data.id, 'terminate')" severity="secondary" label="Terminate"></Button> -->
                        <!-- <Button v-if="data.active == 0" type="button" icon="pi pi-times" @click="toggleAccount(data.id, 'activate')" severity="success" label="Activate"></Button> -->
                        <!-- <router-link :to="{name: 'UserTransactions', params: { id: data.id}}">
                            <Button label="View Transactions" severity="info"></Button>
                        </router-link> -->
                    </template>
                    <!-- <template #filter="{ filterModel }">
                        <label for="verified-filter" class="font-bold"> Verified </label>
                        <TriStateCheckbox v-model="filterModel.value" inputId="verified-filter" />
                    </template> -->
                </Column>
    
            </DataTable>
    
        </div>
    
        <Dialog v-model:visible="paymentModal" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
            <template #container="{ closeCallback }">
                <div class="flex flex-column px-5 py-5 gap-4" style="border-radius: 12px; background-image: radial-gradient(circle at left top, var(--primary-400), var(--primary-700))">
                    <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="block mx-auto">
                        <path
                            d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
                            fill="var(--primary-700)"
                        />
                        <path
                            d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
                            fill="var(--primary-200)"
                        />
                    </svg>
                    <div class="inline-flex flex-column gap-2">
                        <InlineMessage severity="info">Wallet Information</InlineMessage>
                    </div>

                    <div class="inline align-content-center justify-content-center content-center center">
                        <Fieldset legend="Payment Address">
                            <p class="m-0 center">
                                <vue-qrcode class="center" :value="generateAddress"></vue-qrcode>
                            </p>
                        </Fieldset>
                        
                    </div>
    
                    <div class="inline-flex flex-column gap-2">
                        <Button raised :label="wallet_info?.address" icon="pi pi-copy" @click="copyAddress"></Button>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Symbol: {{ wallet_info?.symbol }}</label>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Amount: {{ formatCurrency(Number(wallet_info?.amount ?? 0.00))}}</label>
                    </div>

                    
                    
    
                    
                    <div class="flex align-items-center gap-3">
                        <Button label="Close" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
        </Dialog>
    
    </template>
    
    <script lang="ts">
    
    import { defineComponent, ref, onMounted, watch, reactive, computed } from 'vue'
    import { useGlobalLoader } from 'vue-global-loader'
    import { formatCurrency, formatDate } from '@/lib';
    import { toast } from '@/lib/notifications'
    import { useNetwork } from '@/lib/request'
    import { useRoute } from 'vue-router'
    import { FilterMatchMode, FilterOperator } from 'primevue/api';
    import type { WalletWithdraw } from '@/types/Withdrawal'
    import VueQrcode from '@chenfengyuan/vue-qrcode';
    import Fieldset from 'primevue/fieldset';
    import type { HintedString } from 'primevue/ts-helpers';

    export default defineComponent({
        name: 'ViewWalletWithdrawals',
        props: {
            type: {
                type: String,
                require: true
            }
        },
        setup(props) {
            const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
            const filters = ref();
            const transactions = ref([]);
            const route = useRoute()
            const network = useNetwork(true)
    
            const wallet_info = ref<WalletWithdraw | null>(null)
    
            const paymentModal = ref<boolean>(false)
    
            const initFilters = () => {
                filters.value = {
                    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                    reference: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
                };
            };
            const loadWithdrawals = () => {
                transactions.value = []
                displayLoader()
                // network.fetch('office/transactions/' + props.id).then((response) => {
                //     // console.log(response.data)
                network.fetch('office/wallet_withdrawals/'+props.type, {}).then((response) => { 
                    console.log(response)
                    // @ts-ignore
                    transactions.value = response.data.data ?? response.data
                }).catch((error) => {
                    toast.info('Error', error.message!)
                }).finally(() => {
                    destroyLoader()
                })
            }
    
            const getSeverity = (status: string): HintedString<"secondary" | "success" | "info" | "warning" | "danger" | "contrast"> | undefined => {
                switch(status) {
                    case 'pending':
                        return 'warning'
                    case 'approved':
                        return 'success'
                    case 'cancelled':
                        return 'danger'
                    default:
                        return 'secondary'
                }
            }

            const generateAddress = computed(() => {
                if(wallet_info.value === null || wallet_info.value === undefined) {
                    return 'null'
                }
                const trans = wallet_info.value
                const full = `USD:${trans.address}?amount=${trans.amount}`
                return full
            })
    
            const toggleWithdrawal = (id: string, action: string) => {
                displayLoader()
                network.push('office/toggle_request', { id: id, action: action}).then((response) => {
                    // @ts-expect-error
                    toast.success(response.data)
                    loadWithdrawals()
                }).catch((error) => {
                    toast.error(error.message!)
                }).finally(() => {
                    destroyLoader()
                })
            }
    
            onMounted(() => {
                loadWithdrawals()
            })
    
            const showBankInfo = (request: any) => {
                const wallet = request.wallet_info ?? request.wallet
                if(!wallet) {
                    toast.info('Wallet Info', 'No wallet information is attached to this request')
                    return
                }
                const req: WalletWithdraw = {
                    address: wallet.address,
                    symbol: wallet.symbol,
                    network: wallet.network,
                    amount: request.amount
                }
                wallet_info.value = req
                paymentModal.value = true
            }
    

            const copyAddress = async () => {
                const trans = wallet_info.value
                if(!trans || trans == null) {
                    toast.info('Error', 'Unable to copy')
                    return
                }
                try {
                    await navigator.clipboard.writeText("" + trans.address +"");
                    toast.info('Copied!!', 'Payments address copied to clipboard')
                    
                } catch (error) {
                    toast.error('Unable to copy')
                }
                return
            }

            initFilters()
    
            watch(() => route.params.type, async newType => {
                loadWithdrawals()
            })
    
            return { filters, copyAddress, generateAddress, showBankInfo, paymentModal, wallet_info, toggleWithdrawal, isLoading, transactions, formatCurrency, formatDate, initFilters, getSeverity}
        },
        components: {
            Fieldset
        }
    })
    
    </script>
