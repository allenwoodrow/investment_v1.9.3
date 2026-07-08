<template>
    <div class="grid">
        <div class="card col-12">
            <DataTable v-model:filters="filters" :value="customers" paginator showGridlines :rows="10" dataKey="id"
                filterDisplay="menu" :loading="isLoading" :globalFilterFields="['username', 'email', 'profile.name']">
            <template #header>
                
                <div class="flex justify-content-between">
                    <div class="flex w-500 justify-between">
                        <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter()" />
                        <Button type="button" :disabled="selectedIds.length < 1" icon="pi pi-trash" severity="danger" label="Delete Selected" @click="deleteUsers"></Button>
                    </div>
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Username/Email Search" />
                    </IconField>
                </div>
            </template>
            <template #empty> No customers found. </template>
            <template #loading> Loading users data. Please wait. </template>
            <Column header="">
                <template #body="{ data }">
                    <Checkbox :checked="false" @change="handleUpdate(data.id, $event)" :binary="true"/>
                </template>
            </Column>
            <Column field="username" header="UserName" style="min-width: 12rem">
                <template #body="{ data }">
                    {{ data.username }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by name" />
                </template>
            </Column>
            <Column header="Email" filterField="email" style="min-width: 12rem">
                <template #body="{ data }">
                    <div class="flex align-items-center gap-2">
                        <!-- <img alt="flag" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`flag flag-${data.country.code}`" style="width: 24px" /> -->
                        <span>{{  data.email }}</span>
                    </div>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" class="p-column-filter" placeholder="Search by country" />
                </template>
                <template #filterclear="{ filterCallback }">
                    <Button type="button" icon="pi pi-times" @click="filterCallback()" severity="secondary"></Button>
                </template>
                <template #filterapply="{ filterCallback }">
                    <Button type="button" icon="pi pi-check" @click="filterCallback()" severity="success"></Button>
                </template>
                <template #filterfooter>
                    <div class="px-3 pt-0 pb-3 text-center">Customized Buttons</div>
                </template>
            </Column>
            <Column header="Registered" filterField="created_at" :showFilterMatchModes="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 14rem">
                <template #body="{ data }">
                    <div class="flex align-items-center gap-2">
                        <!-- <img :alt="data.email" :src="`https://primefaces.org/cdn/primevue/images/avatar/${data.representative.image}`" style="width: 32px" /> -->
                        <span>{{ formatDate(data.created_at) }}</span>
                    </div>
                </template>
                <template #filter="{ filterModel }">
                    <!-- <MultiSelect v-model="filterModel.value" :options="representatives" optionLabel="name" placeholder="Any" class="p-column-filter">
                        <template #option="slotProps">
                            <div class="flex align-items-center gap-2">
                                <img :alt="slotProps.option.name" :src="`https://primefaces.org/cdn/primevue/images/avatar/${slotProps.option.image}`" style="width: 32px" />
                                <span>{{ slotProps.option.name }}</span>
                            </div>
                        </template>
                    </MultiSelect> -->
                </template>
            </Column>
            
            <Column header="Balance" filterField="balance" dataType="numeric" style="min-width: 10rem">
                <template #body="{ data }">
                    {{ formatCurrency(data.wallet) }}
                </template>
                <template #filter="{ filterModel }">
                    <InputNumber v-model="filterModel.value" mode="currency" currency="USD" locale="en-US" />
                </template>
            </Column>
            <Column header="Status" field="status" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <Tag :value="getStatus(data.active)" :severity="getSeverity(data.active)" />
                    <!-- <i class="pi" :class="{ 'pi-check-circle text-green-500 ': data.active, 'pi-times-circle text-red-500': !data.active }"></i> -->
                </template>
                <template #filter="{ filterModel }">
                    <Dropdown v-model="filterModel.value" :options="statuses" placeholder="Select One" class="p-column-filter" showClear>
                        <template #option="slotProps">
                            <!-- <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" /> -->
                        </template>
                    </Dropdown>
                </template>
            </Column>
            
            <Column field="verified" header="Actions" dataType="boolean" bodyClass="text-center" style="min-width: 8rem">
                <template #body="{ data }">
                    <Button v-if="data.active === 1" type="button" icon="pi pi-times" @click="toggleAccount(data.id, 'suspend')" severity="danger" label="Suspend"></Button>
                    <Button v-if="data.active === 0" type="button" icon="pi pi-times" @click="toggleAccount(data.id, 'activate')" severity="success" label="Activate"></Button>
                    <router-link :to="{name: 'UserTransactions', params: { id: data.id}}">
                        <Button icon="pi pi-eye" label="View Transactions" severity="info"></Button>
                    </router-link>
                    <Button v-if="data.multi === false || data.multi === '0'" icon="pi pi-lock" severity="secondary" label="Activate 2FA" @click="toggle_2fa(data.id, true)"></Button>
                    <Button v-if="data.multi === '1'" icon="pi pi-lock" severity="warning" label="Deactivate 2FA" @click="toggle_2fa(data.id, false)"></Button>
                    <Button icon="pi pi-trash" severity="danger" label="Delete" @click="deleteUser(data.id)"></Button>
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
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import { formatCurrency, formatDate } from '@/lib';
import { useNetwork } from '@/lib/request'
import { toast } from '@/lib/notifications'
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import { useGlobalLoader } from 'vue-global-loader';
import type { PaginatedResponse } from '@/types/response'
import type { Profile } from '@/types/Auth'
import type { HintedString } from 'primevue/ts-helpers';
import Checkbox from 'primevue/checkbox';

export default defineComponent({

    setup() {
        const network = useNetwork(true)
        const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()
        const customers = ref();
        const filters = ref();
        const statuses = [1, 0]
        const selectedIds = ref<Number[]>([]);
        
        onMounted(() => {
            displayLoader()
            loadUsers()
            
        });
        const loadUsers = () => {
            network.fetch('office/users', {}).then((response) => {
                console.log(response)
                destroyLoader()
                customers.value = response.data
                console.log(customers.value)
            }).catch((error) => {
                destroyLoader()
                console.log(error)
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
        
        const getSeverity = (status: number): HintedString<"secondary" | "success" | "info" | "warning" | "danger" | "contrast"> | undefined => {
            switch (status) {
                case 0:
                    return 'danger';

                case 1:
                    return 'success'
            }
            return undefined
        }
        const getStatus = (status: number): string => {
            return (status == 1) ? 'active' : 'inactive'
        }

        const toggleAccount = (id: number, status: string) => {
            displayLoader()
            network.fetch('office/toggleAccount/'+id+'/'+status, {}).then((response) => {
                console.log(response)
                // @ts-expect-error
                const message = response.data?.message || 'Account updated successfully'
                toast.success(message)
                loadUsers()
            }).catch((error) => {
                toast.error(error.message || 'Failed to update account')
            }).finally(() => {
                destroyLoader()
            })
        }

        const toggle_2fa = (id: string, action: boolean) => {
            displayLoader()
            network.push<string, {}>('office/toggle_2fa', {id: id, action: action}).then(response => {
                const message = response.data?.message || response.data || '2FA status updated'
                toast.success(typeof message === 'string' ? message : 'Success')
                loadUsers()
            }).catch(error => {
                toast.error(error.message || 'Failed to update 2FA')
            }).finally(() => destroyLoader())
        }

        const deleteUser = async (id: number) => {
            displayLoader()
            network.push('office/deleteUser', {id: id}).then((response) => {
                // @ts-ignore
                const message = response.data?.message || 'User deleted successfully'
                toast.success(message);
                loadUsers()
            }).catch(error => {
                toast.error(error.message || 'Failed to delete user')
            }).finally(() => destroyLoader())

        }

        const handleUpdate = (id: string, newValue: Event) => {
            //  @ts-ignore
            if(newValue.target.checked === true) {
                if(!selectedIds.value.includes(Number(id))) {
                    selectedIds.value.push(Number(id))
                }
            } else {
                if(selectedIds.value.includes(Number(id))) {
                    const index = selectedIds.value.indexOf(Number(id))
                    selectedIds.value.splice(index, 1);
                }
            }
        };

        const deleteUsers = () => {
            if(selectedIds.value.length === 0) {
                toast.error('No users selected')
                return
            }
            displayLoader()
            network.push('office/deleteUsers', {ids: selectedIds.value}).then((response) => {
                // @ts-ignore
                const message = response.data?.message || 'Users deleted successfully'
                toast.success(message)
                loadUsers()
            }).catch(error => {
                toast.error(error.message || 'Failed to delete users')
            }).finally(() => destroyLoader())
        }

        // const manageList = (value: any, id: number) => {
        //     alert(id);
        //     alert(value);
        // }

        watch(isLoading, (newVal) => {
            if(newVal === false) {
                destroyLoader()
            }
        })
        return {toggle_2fa, selectedIds, deleteUser, deleteUsers, handleUpdate, customers, getStatus, toggleAccount, statuses, filters, isLoading, initFilters, formatCurrency, getSeverity, formatDate, clearFilter }
    },
    components: {
        Checkbox
    }
})
</script>