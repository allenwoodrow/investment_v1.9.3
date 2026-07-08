<template>
    <div class="grid">
        <div class="card col-12">
            <DataTable v-model:filters="filters" :value="codes" paginator showGridlines :rows="10" dataKey="id"
                filterDisplay="menu" :loading="isLoading" :globalFilterFields="['username']">
                <template #header>
                    <div class="flex justify-content-between">
                        <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter()" />
                        <IconField iconPosition="left">
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Username Search" />
                        </IconField>
                    </div>
                </template>
                <template #empty> No code found. </template>
                <template #loading> Loading codes data. Please wait. </template>
                <Column field="code" header="Code" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{ data.code }}
                    </template>
                    <!-- <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by name" />
                    </template> -->
                </Column>

                <Column field="user.username" header="Username" style="min-width: 12rem">
                    <template #body="{ data }">
                        {{  data.user.username }}
                    </template>
                    <!-- <template #filter="{ filterModel }">
                        <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by name" />
                    </template> -->
                </Column>
            
           
            
                <Column field="verified" header="Actions" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                    <template #body="{ data }">
                        <Button type="button" icon="pi pi-copy" @click="copyCode(data.code)" severity="info" label="Copy"></Button>
                        <Button type="button" icon="pi pi-trash" @click="deleteCode(data.id)" severity="danger" label="Delete"></Button>

                        <!-- 

                        <Button v-if="data.active == 0" type="button" icon="pi pi-times" @click="toggleAccount(data.id, 'activate')" severity="success" label="Activate"></Button>
                        <router-link :to="{name: 'UserTransactions', params: { id: data.id}}">
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
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, reactive, watch } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { formatCurrency, formatDate } from '@/lib';
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import { useGlobalLoader } from 'vue-global-loader';
import type { PaginatedResponse } from '@/types/response'
import type { HintedString } from 'primevue/ts-helpers';
import Dropdown from 'primevue/dropdown';


export default defineComponent({
    name: 'MFA_Management',
    setup() {
        const network = useNetwork(true)
        const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
        const filters = ref();
        const codes = ref()
                
        onMounted(() => {
            displayLoader()
            loadData()
        });

        const loadData = () => {
            network.fetch('office/otp_codes', {}).then((response) => {
                codes.value = response.data
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => {
                destroyLoader()
            })
        }

        
        const initFilters = () => {
            filters.value = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS }
            };
        };

        initFilters();

        const clearFilter = () => {
            initFilters();
        };

        const deleteCode = (id: string) => {
            displayLoader()
            network.push('office/delete_otp', {id: id}).then((response) => {
                toast.success(response.data as string)
                loadData()
            }).catch((error) => {
                toast.error(error.message!)
            }).finally(() => {
                destroyLoader()
            })
        }
       
        return { codes, deleteCode, filters, isLoading, initFilters, formatCurrency, formatDate, clearFilter }
    },
    components: {
        Dropdown
    },
    methods: {
        async copyCode(code: string) {
            if(code.length < 1) {
                toast.error('Code is empty')
                return
            }
            try {
                await navigator.clipboard.writeText("" + code +"");
                toast.info('Copied!!', 'Code copied to clipboard')
            } catch (error) {
                toast.error('Unable to copy')
            }
            return
        }
    }
})
</script>