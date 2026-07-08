<template>
    <div class="card">
            <div class="flex justify-content-between mb-3">
                <Button type="button" icon="pi pi-refresh" label="Refresh" outlined @click="loadWithdrawals" />
                <span v-if="isLoading" class="text-500">Loading withdrawal requests...</span>
            </div>

            <div v-if="!isLoading && transactions.length === 0" class="p-4 text-center text-500">
                No withdrawal requests found.
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full withdrawal-table">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Username</th>
                            <th>Bank</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="request in transactions" :key="request.id">
                            <td>{{ formatCurrency(Number(request.amount)) }}</td>
                            <td>{{ request.user?.username ?? 'Unknown user' }}</td>
                            <td>
                                <Button label="View" icon="pi pi-eye" severity="info" @click="showBankInfo(request)"></Button>
                            </td>
                            <td>
                                <Tag :value="request.status" :severity="getSeverity(request.status)" />
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <Button v-if="request.status == 'pending'" type="button" icon="pi pi-times" @click="toggleWithdrawal(request.id, 'reject')" severity="danger" label="Reject"></Button>
                                    <Button v-if="request.status == 'pending'" type="button" icon="pi pi-check" @click="toggleWithdrawal(request.id, 'approve')" severity="success" label="Approve"></Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
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
                        <InlineMessage severity="info">Bank Information</InlineMessage>
                    </div>
    
                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Bank Name: {{ bank_info?.beneficiary_bank }}</label>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Account Name: {{ bank_info?.beneficiary_name }}</label>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Account Number: {{ bank_info?.beneficiary_account }}</label>
                    </div>

                    
                    
    
                    
                    <div class="flex align-items-center gap-3">
                        <Button label="Close" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
        </Dialog>
    
    </template>
    
    <script lang="ts">
    
    import { defineComponent, ref, onMounted, watch } from 'vue'
    import { useGlobalLoader } from 'vue-global-loader'
    import { formatCurrency, formatDate } from '@/lib';
    import { useNetwork } from '@/lib/request'
    import { toast } from '@/lib/notifications'
    import { useRoute } from 'vue-router'
    import type { BankWithdraw } from '@/types/Withdrawal'
    import type { HintedString } from 'primevue/ts-helpers';

    interface BankWithdrawalRow {
        id: string | number
        amount: string | number
        status: string
        user?: {
            username?: string
        } | null
        bank?: {
            bank_name?: string
            account_number?: string
            account_name?: string
            routing_no?: string
            sort_code?: string
        } | null
        bank_info?: {
            bank_name?: string
            account_number?: string
            account_name?: string
            routing_no?: string
            sort_code?: string
        } | null
        bank_name?: string
    }

    export default defineComponent({
        name: 'ViewBankWithdrawals',
        props: {
            type: {
                type: String,
                require: true
            }
        },
        setup(props) {
            const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
            const transactions = ref<BankWithdrawalRow[]>([]);
            const route = useRoute()
            const network = useNetwork(true)
    
            const bank_info = ref<BankWithdraw | null>(null)
    
            const paymentModal = ref<boolean>(false)
    
            const normalizeWithdrawal = (request: any): BankWithdrawalRow => {
                const bank = request.bank ?? request.bank_info ?? null

                return {
                    ...request,
                    user: request.user ?? { username: 'Unknown user' },
                    bank,
                    bank_name: bank?.bank_name ?? '',
                }
            }

            const loadWithdrawals = () => {
                transactions.value = []
                displayLoader()
                network.fetch('office/bank_withdrawals/'+props.type, {}).then((response) => { 
                    const payload: any = response.data
                    const rows = Array.isArray(payload)
                        ? payload
                        : Array.isArray(payload?.data)
                            ? payload.data
                            : []

                    transactions.value = rows.map(normalizeWithdrawal)
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
                const bank = request.bank ?? request.bank_info
                if(!bank) {
                    toast.info('Bank Info', 'No bank information is attached to this request')
                    return
                }
                bank_info.value = {
                    beneficiary_bank: bank.bank_name,
                    beneficiary_account: bank.account_number,
                    beneficiary_name: bank.account_name,
                    amount: 0.00,
                    description: '',
                    routing_no: '',
                    sort_code: ''
                }
                paymentModal.value = true
            }
            watch(() => route.params.type, async newType => {
                loadWithdrawals()
            })
    
            return { showBankInfo, paymentModal, bank_info, toggleWithdrawal, isLoading, transactions, formatCurrency, formatDate, loadWithdrawals, getSeverity}
        }
    })
    
    </script>

    <style scoped>
    .withdrawal-table {
        border-collapse: collapse;
    }

    .withdrawal-table th,
    .withdrawal-table td {
        border: 1px solid var(--surface-border);
        padding: 0.75rem;
        text-align: left;
        vertical-align: middle;
    }

    .withdrawal-table th {
        color: var(--text-color);
        background: var(--surface-ground);
        font-weight: 600;
    }
    </style>
