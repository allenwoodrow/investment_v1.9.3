<template>
    <div class="card">
        <DataTable v-model:filters="filters" :value="transactions" paginator :rows="25" :rowsPerPageOptions="[25, 50, 100]" showGridlines dataKey="id" filterDisplay="menu" :loading="isLoading" :globalFilterFields="['payment_id', 'username', 'user.username']">
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
            <Column field="payment_id" header="Payment ID" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ data.payment_id }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by ID" />
                </template>
            </Column>

            <Column field="username" header="Username" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ data.username ?? data.user?.username ?? 'Unknown' }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by username" />
                </template>
            </Column>

            <Column field="price_amount" header="Amount" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ formatCurrency(Number(data.price_amount)) }}
                </template>
            </Column>

            <Column field="pay_amount" header="Paid" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ Number(data.pay_amount).toFixed(2) ?? 0.00 }} {{ data.pay_currency }}
                </template>
            </Column>

            <Column field="created_at" header="Date" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ formatDate(data.created_at) }}
                </template>
            </Column>

            <Column field="order_description" header="Description" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ data.order_description  }}
                </template>
            </Column>

            <Column field="payment_status" header="Status" style="min-width: 12rem">
                <template #body="{ data }">
                    <Tag :value="data.payment_status" :severity="getSeverity(data.payment_status)" />
                </template>
            </Column>

            <Column field="verified" header="Actions" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                <template #body="{ data }">
                    <Button v-if="data.payment_status == 'waiting' && data.type == 'wdeposit'" type="button" icon="pi pi-check" @click="togglePayment(data.id, 'approve')" severity="success" label="Approve"></Button>
                    <Button v-if="data.payment_status == 'waiting' && data.type == 'wdeposit'" type="button" icon="pi pi-times" @click="togglePayment(data.id, 'reject')" severity="warning" label="Reject"></Button>
                    <Button v-if="data.payment_status == 'finished' && data.type == 'wdeposit'" type="button" icon="pi pi-undo" @click="togglePayment(data.id, 'reverse')" severity="danger" label="Reverse"></Button>
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

</template>


<script lang="ts">
import { defineComponent, ref, onMounted, watch } from 'vue'
import { useGlobalLoader } from 'vue-global-loader';
import { formatCurrency, formatDate } from '@/lib';
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import type { HintedString } from 'primevue/ts-helpers';
import { useRoute } from 'vue-router'

export default defineComponent({
    name: 'ViewTransactions',
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
        const network = useNetwork(true)
        const route = useRoute()

        onMounted(() => {
            loadTransactions()
        })

        const initFilters = () => {
            filters.value = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                reference: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
            };
        };

        const loadTransactions = () => {
            transactions.value = []
            displayLoader()
            // network.fetch('office/transactions/' + props.id).then((response) => {
            //     // console.log(response.data)
            network.fetch('office/deposits/'+props.type, {}).then((response) => { 
                // console.log(response)
                // @ts-ignore
                transactions.value = response.data.data
            }).catch((error) => {
                toast.info('Error', error.message!)
            }).finally(() => {
                destroyLoader()
            })
        }

        const getSeverity = (status: string): HintedString<"secondary" | "success" | "info" | "warning" | "danger" | "contrast"> | undefined => {
            switch(status) {
                case 'waiting':
                    return 'warning'
                case 'finished':
                    return 'success'
                case 'failed':
                    return 'danger'
                case 'refunded':
                    return 'danger'
                default:
                    return 'secondary'
            }
        }

        const togglePayment = (id: string, action: string) => {
            network.push('office/toggleDeposit', { id: id, action: action}).then((response) => {
                // @ts-expect-error
                toast.success(response.data.message)
                loadTransactions()
            }).catch((error) => {

            }).finally(() => {
                destroyLoader()
            })
        }

        watch(() => route.params.type, async newType => {
            loadTransactions()
        })

        initFilters();

        return { isLoading, filters, transactions, initFilters, formatCurrency, getSeverity, togglePayment, formatDate }
    },
    // onBe
})
</script>