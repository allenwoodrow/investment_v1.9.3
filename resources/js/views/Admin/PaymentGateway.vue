<template>
    <div class="card">
        <Toolbar class="mb-4">
            <template v-slot:start>
                <div class="my-2">
                    <Button label="New" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                </div>
            </template>

            <template v-slot:end>
               
            </template>
        </Toolbar>

            <DataTable v-model:filters="filters" :value="transactions" paginator :rows="25" :rowsPerPageOptions="[25, 50, 100]" showGridlines dataKey="id" filterDisplay="menu" :loading="isLoading" :globalFilterFields="['currency']">
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
                <template #empty>No Wallets Found. </template>
                <template #loading>Loading payment data. Please wait. </template>
                <Column field="currency" header="Currency Symbol" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ data.symbol.toUpperCase() }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by ID" />
                    </template>
                </Column>

                <Column field="currency" header="Currency Name" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by ID" />
                    </template>
                </Column>
    
                <Column field="address" header="Address" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ data.address }}
                        <!-- {{ formatCurrency(Number(data.price_currency)) }} -->
                    </template>
                </Column>
    
                
                <Column field="status" header="Actions" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                    <template #body="{ data }">
                        <Button type="button" icon="pi pi-upload" @click="startEdit(data.id, data.name, data.symbol, data.address)" severity="info" label="Edit"></Button>
                    </template>
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
                        <InlineMessage severity="info">Set Wallet</InlineMessage>
                    </div>
    
                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Currency Name</label>
                        <InputText :focus="true" v-model="gateway_form.name" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Currency Symbol</label>
                        <InputText :focus="true" v-model="gateway_form.symbol" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Address</label>
                        <InputText :focus="true" v-model="gateway_form.address" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>
                    
    
                    
                    <div class="flex align-items-center gap-3">
                        <Button label="Cancel" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                        <Button label="Confirm" @click="next" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
        </Dialog>

        <Dialog v-model:visible="addModal" modal :pt="{ root: 'border-none', mask: { style: 'backdrop-filter: blur(2px)'}}">
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
                        <InlineMessage severity="info">Add Payment Wallet</InlineMessage>
                    </div>
    
                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Currency Name</label>
                        <InputText :focus="true" v-model="add_form.name" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Currency Symbol</label>
                        <InputText :focus="true" v-model="add_form.symbol" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>

                    <div class="inline-flex flex-column gap-2">
                        <label for="username" class="text-primary-50 font-semibold">Address</label>
                        <InputText :focus="true" v-model="add_form.address" class="border-none p-3 text-primary-50 w-20rem"></InputText>
                    </div>
                    
    
                    
                    <div class="flex align-items-center gap-3">
                        <Button label="Cancel" @click="closeCallback" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                        <Button label="Confirm" @click="addGateway" text class="p-3 w-full text-primary-50 border-1 border-white-alpha-30 hover:bg-white-alpha-10"></Button>
                    </div>
                </div>
            </template>
        </Dialog>
    
    </template>
    
    <script lang="ts">
    
    import { defineComponent, ref, onMounted, watch, reactive } from 'vue'
    import { useGlobalLoader } from 'vue-global-loader'
    import { formatCurrency, formatDate } from '@/lib';
    import { useNetwork } from '@/lib/request'
    import { toast } from '@/lib/notifications'
    import { useRoute } from 'vue-router'
    import { FilterMatchMode, FilterOperator } from 'primevue/api';
    import type { HintedString } from 'primevue/ts-helpers';
    
    
    export default defineComponent({
        name: 'PaymentGateway',
        props: {
            type: {
                type: String,
                require: true
            }
        },
        setup(props) {
            const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
            const filters = ref();
            const transactions = ref();
            const route = useRoute()
            const network = useNetwork(true)
    
            const gateway_form = reactive({
                id: '',
                symbol: '',
                name: '',
                address: ''
            })

            const add_form = reactive({
                name: '',
                symbol: '',
                address: ''
            })
    
            const paymentModal = ref<boolean>(false)
            const addModal = ref<boolean>(false)
    
            const initFilters = () => {
                filters.value = {
                    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                    reference: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
                };
            };
            const loadWallets = () => {
                transactions.value = []
                displayLoader()
                //     // console.log(response.data)
                network.fetch('office/payment_gateway', {}).then((response) => {
                    console.log(response)
                    // @ts-ignore
                    transactions.value = response.data
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
                    case 'complete':
                        return 'success'
                    case 'cancelled':
                        return 'danger'
                    default:
                        return 'secondary'
                }
            }
    
            onMounted(() => {
                loadWallets()
            })
    
            const startEdit = (id: string, name: string, symbol: string, address: string, ) => {
                gateway_form.id = id
                gateway_form.name = name
                gateway_form.address = address
                gateway_form.symbol = symbol
                paymentModal.value = true
            }

            const openNew = () => {
                addModal.value = true
            }
    
            const next = () => {
                displayLoader()
    
                network.push<string, {}>('office/patch_gateway', gateway_form)
                .then((response) => {
                    toast.success(response.data!)
                    paymentModal.value = false
                    loadWallets()
                })
                .catch((error) => {
                    // paymentModal
                    toast.error(error.message!)
                })
                .finally(() => {
                    destroyLoader()
                })
            }

            const addGateway = () => {
                displayLoader()
    
                network.push<string, {}>('office/add_gateway', add_form)
                .then((response) => {
                    toast.success(response.data!)
                    addModal.value = false
                    loadWallets()
                })
                .catch((error) => {
                    // paymentModal
                    toast.error(error.message!)
                })
                .finally(() => {
                    destroyLoader()
                })
            }
    
            initFilters()
    
            watch(() => route.params.type, async newType => {
                loadWallets()
            })
    
            return { add_form, filters, addGateway, next, startEdit, openNew, addModal, paymentModal, gateway_form, isLoading, transactions, formatCurrency, formatDate, initFilters, getSeverity}
        }
    })
    
    </script>