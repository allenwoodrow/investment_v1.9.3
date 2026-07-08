<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <!-- <Button label="New" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" /> -->
                            <Button label="Delete All" icon="pi pi-times" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedmessages || !selectedmessages.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <!-- <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import" class="mr-2 inline-block" /> -->
                        <!-- <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV()" /> -->
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="messages"
                    v-model:selection="selectedmessages"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} messages"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Messages</h5>
                            <IconField iconPosition="left" class="block mt-2 md:mt-0">
                                <InputIcon class="pi pi-search" />
                                <InputText class="w-full sm:w-auto" v-model="filters['global'].value" placeholder="Search..." />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="code" header="Name" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Name</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="name" header="Email" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Email</span>
                            {{ slotProps.data.email }}
                        </template>
                    </Column>
                    <Column header="Message" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Message</span>
                            <p>{{ slotProps.data.message }}</p>
                        </template>
                    </Column>
                    
                    
                    <Column header="Action" headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-copy" class="mr-2" severity="success" rounded @click="copy(slotProps.data.email)" label="Copy Email" />
                            <Button icon="pi pi-copy" class="mr-2" severity="secondary" rounded @click="copy(slotProps.data.name)" label="Copy Name" />
                            <Button icon="pi pi-trash" class="mr-2" severity="danger" rounded @click="deleteMessage(slotProps.data.id)" label="Delete" />
                            
                        </template>
                    </Column>
                </DataTable>

                
                <Dialog v-model:visible="deletemessagesDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="product">Are you sure you want to delete the selected messages?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deletemessagesDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedmessages" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>

</template>


<script lang="ts">
import { defineComponent, onBeforeMount, ref, reactive, computed, onMounted, watch } from 'vue';
import Card from 'primevue/card';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import { FilterMatchMode } from 'primevue/api';
import { formatCurrency } from '@/lib';
import type { Message } from '@/types/Contact'
import type { InvestmentPlan } from '@/types/investments'
import { required, numeric } from '@vuelidate/validators'
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'   // optional
import { useNetwork } from '@/lib/request';
import { toast } from '@/lib/notifications'
import { useGlobalLoader } from 'vue-global-loader'

export default defineComponent({
    name: 'ViewMessages',
    setup() {
        const defaultProduct: InvestmentPlan = {
            name: '',
            id: 0,
            description: '',
            investment_term: '',
            return_percent: '',
            min_amount: '',
            active: true,
            features: []
        }

        const product = ref<InvestmentPlan|null>(null)
        const newDialog = ref(false)
        const productDialog = ref(false);
        const filters = ref();
        const messages = ref<Message[]>([]);
        const selectedmessages = ref<InvestmentPlan[]>([]);
        const dt = ref(null);
        const deletemessagesDialog = ref(false);

        const { isLoading, displayLoader, destroyLoader } = useGlobalLoader()

        const rules = computed(() => {
            return {
                name: {required},
                description: { required },
                investment_term: { required },
                return_percent: { required, numeric },
                min_amount: {required, numeric},
                active: { required },
            }
        })

        const network = useNetwork(true)

        const initFilters = () => {
            filters.value = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            };
        };

        onBeforeMount(() => {
            initFilters()
        })

        onMounted(() => {
            loadMessages()
        })

        const openNew = () => {
            product.value = defaultProduct
            newDialog.value = true
        };

        const hideDialog = () => {
            productDialog.value = false;
        };

        const confirmDeleteProduct = (editProduct: {}) => {
            // product.value = editProduct;
            // deleteProductDialog.value = true;
        };
        const deleteMessage = (id: string) => {
            displayLoader()
            network.push<string, {}>('office/delete_message', {id: id}).then( (response) => {
                toast.success(response.data!)
                loadMessages()
            })
            .catch(error => toast.error(error.message!))
            .finally(() => destroyLoader())
        };

        const createId = () => {
            // let id = '';
            // const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            // for (let i = 0; i < 5; i++) {
            //     id += chars.charAt(Math.floor(Math.random() * chars.length));
            // }
            // return id;
        };

        const loadMessages = async () => {
            displayLoader()
            network.fetch('office/messages', {}).then((response) => {
                // console.log(response)
                //  @ts-ignore
                messages.value = response.data!.data
            })
            .catch((error) => {
                toast.error('Unable to load investment plans: '+error.message!)
            })
            .finally(() => { destroyLoader() })
        }

        const exportCSV = () => {
            // dt.value.exportCSV();
        };

        const confirmDeleteSelected = () => {
            deletemessagesDialog.value = true;
        };
        const deleteSelectedmessages = () => {
            const selected = {
                // @ts-ignore
                ids: selectedmessages.value.map(c => c.id)
            }
            displayLoader()
            network.push<string, {}>('office/toggle_plans', selected).then((response) => {
                toast.success(response.data!)
                loadMessages()
            }).catch((error) => {
                console.log(error.message!)
            }).finally(() => {
                destroyLoader()
                deletemessagesDialog.value = false;
            })
        };

        const getBadgeSeverity = (inventoryStatus: string) => {
            switch (inventoryStatus.toLowerCase()) {
                case 'instock':
                    return 'success';
                case 'lowstock':
                    return 'warning';
                case 'outofstock':
                    return 'danger';
                default:
                    return 'info';
            }
        };

      

        const toggleProduct = (id: string, status: string) => {
            displayLoader()
            network.push<string, {}>('office/toggle_plan', {id: id, status: status}).then((response) => {
                toast.success(response.data!)
                loadMessages()
            })
            .catch(error => toast.info('Error', error.message))
            .finally(() => { destroyLoader() })
        }

        const copy = async (text: string) => {
            try {
                await navigator.clipboard.writeText("" + text +"");
                toast.success('Copied to clipboard!!',)
            } catch (error) {
                toast.error('Unable to copy')
            }
            return
        }


        return {product,  filters, deletemessagesDialog, toggleProduct, isLoading, messages, dt, getBadgeSeverity, selectedmessages,  confirmDeleteProduct, deleteMessage, openNew, hideDialog, confirmDeleteSelected, deleteSelectedmessages, exportCSV, copy }
    },
    components: {
        Card,
        DataViewLayoutOptions,
        DataView
    },
    methods: {
        formatCurrency,

    }
})
</script>