<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="New" icon="pi pi-plus" class="mr-2" severity="success" @click="openNew" />
                            <Button label="Deactivate" icon="pi pi-times" severity="danger" @click="confirmDeleteSelected" :disabled="!selectedProducts || !selectedProducts.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <!-- <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import" class="mr-2 inline-block" /> -->
                        <!-- <Button label="Export" icon="pi pi-upload" severity="help" @click="exportCSV()" /> -->
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="products"
                    v-model:selection="selectedProducts"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Products</h5>
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
                    <Column field="name" header="Description" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Description</span>
                            {{ slotProps.data.description }}
                        </template>
                    </Column>
                    <Column header="Minimum Amount" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Minimum Amount</span>
                            {{ formatCurrency(Number(slotProps.data.min_amount)) }}
                            <!-- <img :src="'/demo/images/product/' + slotProps.data.image" :alt="slotProps.data.image" class="shadow-2" width="100" /> -->
                        </template>
                    </Column>
                    <Column field="price" header="Investment Term" :sortable="true" headerStyle="width:14%; min-width:8rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Investment Term</span>
                            {{ slotProps.data.investment_term }}
                            <!--  -->
                        </template>
                    </Column>
                    <Column field="category" header="Category" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">ROI Percentage</span>
                            %{{  Number(slotProps.data.return_percent).toFixed(1) }}
                            <!-- {{ slotProps.data.category }} -->
                        </template>
                    </Column>
                    <Column field="rating" header="Features" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Features</span>
                            <ul>
                                <li v-for="item in slotProps.data.details">{{ item.feature }}</li>
                            </ul>
                            <!-- <Rating :modelValue="slotProps.data.rating" :readonly="true" :cancel="false" /> -->
                        </template>
                    </Column>
                    
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button v-if="slotProps.data.active == false" icon="pi pi-check" class="mr-2" severity="success" rounded @click="toggleProduct(slotProps.data.id, 'activate')" label="Activate" />
                            <Button v-if="slotProps.data.active == true" icon="pi pi-times" class="mr-2" severity="warning" rounded @click="toggleProduct(slotProps.data.id, 'deactivate')" label="Deactivate" />
                            <Button icon="pi pi-pencil" class="mr-2" severity="info" rounded @click="editProduct(slotProps.data)" label="Edit" />
                            <!-- <Button icon="pi pi-trash" class="mt-2" severity="danger" rounded @click="confirmDeleteProduct(slotProps.data)" /> -->
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="newDialog" :style="{ width: '450px' }" header="Add Plan" :modal="true" class="p-fluid">
                    <!-- <img :src="'/demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
                    <div class="field">
                        <label for="name">Plan Name</label>
                        <InputText id="name" v-model.trim="productForm.name" required="true" autofocus :invalid="v$.name.$error" />
                        <small class="p-invalid" v-if="v$.name.$error">{{ v$.name.$errors[0].$message }}</small>
                        <!-- <small class="p-invalid" v-if=" && !product.name">Name is required.</small> -->
                    </div>
                    <div class="field">
                        <label for="description">Description</label>
                        <InputText id="name" v-model.trim="productForm.description" required="true" autofocus :invalid="v$.description.$error" />
                        <small class="p-invalid" v-if="v$.description.$error">{{ v$.description.$errors[0].$message }}</small>
                        <!-- <Textarea id="description" v-model="product.description" required="true" rows="3" cols="20" /> -->
                    </div>

                    <div class="field">
                        <label for="name">Minimum Investment ($)</label>
                        <InputText id="name" v-model.trim="productForm.min_amount" required="true" autofocus :invalid="v$.min_amount.$error" />
                        <small class="p-invalid" v-if="v$.min_amount.$error">{{ v$.min_amount.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">Investment Term</label>
                        <InputText id="name" v-model="productForm.investment_term" required="true" autofocus :invalid="v$.investment_term.$error" placeholder="3 months"/>
                        <small class="p-invalid" v-if="v$.investment_term.$error">{{ v$.investment_term.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">ROI Percentage</label>
                        <InputText id="name" v-model="productForm.return_percent" required="true" autofocus :invalid="v$.return_percent.$error" placeholder="20" />
                        <small class="p-invalid" v-if="v$.return_percent.$error">{{ v$.return_percent.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">Plan Features</label>
                        <div>
                            <InputGroup className="flex flex-column md:flex-row" >
                                <InputText placeholder="Negative Balance" v-model="planFeature" className="col-10 sm:col-10 md:col-10"/>
                                <Button icon="pi pi-plus" severity="success" @click="addFeature" className="col-2" />
                            </InputGroup>
                            <small class="p-invalid" v-if="newFeatures.length == 0">Please add at least one feature</small>

                        </div>

                    </div>

                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Added Features</label>
                        <DataView :value="newFeatures" dataKey="value">
                            <template #list="slotProps">
                                <div class="grid grid-nogutter">
                                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                                        <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3" :class="{ 'border-top-1 surface-border': index !== 0 }">
                                            
                                            <div class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                                <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <div class="text-lg font-medium text-900 mt-2">{{ item }}</div>
                                                    </div>
                                                </div>
                                            <div class="flex flex-column md:align-items-end gap-5">
                                                <div class="flex flex-row-reverse md:flex-row gap-2">
                                                    <Button icon="pi pi-trash" severity="danger" outlined @click="removeFeature(index)"></Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </template>
                        </DataView>
                    </div>

                    <template #footer>
                        <!-- <Button label="Cancel" icon="pi pi-times" :text="'Cancel'" @click="hideDialog" /> -->
                        <Button :loading="isLoading" raised severity="success" icon="pi pi-check" label="Save" @click="addProduct" />
                    </template>
                </Dialog>

                <Dialog v-model:visible="productDialog" :style="{ width: '450px' }" header="Add Plan" :modal="true" class="p-fluid">
                    <!-- <img :src="'/demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
                    <div class="field">
                        <label for="name">Plan Name</label>
                        <InputText id="name" v-model.trim="editForm.name" required="true" autofocus :invalid="v$.name.$error" />
                        <small class="p-invalid" v-if="e$.name.$error">{{ e$.name.$errors[0].$message }}</small>
                        <!-- <small class="p-invalid" v-if=" && !product.name">Name is required.</small> -->
                    </div>
                    <div class="field">
                        <label for="description">Description</label>
                        <InputText id="name" v-model.trim="editForm.description" required="true" autofocus :invalid="e$.description.$error" />
                        <small class="p-invalid" v-if="e$.description.$error">{{ e$.description.$errors[0].$message }}</small>
                        <!-- <Textarea id="description" v-model="product.description" required="true" rows="3" cols="20" /> -->
                    </div>

                    <div class="field">
                        <label for="name">Minimum Investment ($)</label>
                        <InputText id="name" v-model.trim="editForm.min_amount" required="true" autofocus :invalid="e$.min_amount.$error" />
                        <small class="p-invalid" v-if="e$.min_amount.$error">{{ e$.min_amount.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">Investment Term</label>
                        <InputText id="name" v-model="editForm.investment_term" required="true" autofocus :invalid="e$.investment_term.$error" placeholder="3 months"/>
                        <small class="p-invalid" v-if="e$.investment_term.$error">{{ e$.investment_term.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">ROI Percentage</label>
                        <InputText id="name" v-model="editForm.return_percent" required="true" autofocus :invalid="e$.return_percent.$error" placeholder="20" />
                        <small class="p-invalid" v-if="e$.return_percent.$error">{{ e$.return_percent.$errors[0].$message }}</small>
                    </div>

                    <div class="field">
                        <label for="name">Plan Features</label>
                        <div>
                            <InputGroup className="flex flex-column md:flex-row" >
                                <InputText placeholder="Negative Balance" v-model="planFeature" className="col-10 sm:col-10 md:col-10"/>
                                <Button icon="pi pi-plus" severity="success" @click="addFeature" className="col-2" />
                            </InputGroup>
                            <small class="p-invalid" v-if="newFeatures.length == 0">Please add at least one feature</small>

                        </div>

                    </div>

                    <div class="field">
                        <label for="inventoryStatus" class="mb-3">Added Features</label>
                        <DataView :value="newFeatures" dataKey="value">
                            <template #list="slotProps">
                                <div class="grid grid-nogutter">
                                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                                        <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3" :class="{ 'border-top-1 surface-border': index !== 0 }">
                                            
                                            <div class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                                <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <div class="text-lg font-medium text-900 mt-2">{{ item }}</div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-column md:align-items-end gap-5">
                                                    <div class="flex flex-row-reverse md:flex-row gap-2">
                                                        <Button icon="pi pi-trash" severity="danger" outlined @click="removeFeature(index)"></Button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </DataView>
                    </div>

                    <template #footer>
                        <!-- <Button label="Cancel" icon="pi pi-times" :text="'Cancel'" @click="hideDialog" /> -->
                        <Button :loading="isLoading" raised severity="success" icon="pi pi-check" label="Save" @click="saveProduct" />
                    </template>
                </Dialog>
                <!-- <Dialog v-model:visible="deleteProductDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="product"
                            >Are you sure you want to delete <b>{{ product.name }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteProductDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteProduct" />
                    </template>
                </Dialog> -->

                <Dialog v-model:visible="deleteProductsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="product">Are you sure you want to deactivate the selected products?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" text @click="deleteProductsDialog = false" />
                        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedProducts" />
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
import type { InvestmentPlan, InvestmentPlanDetail } from '@/types/investments';
import { useVuelidate } from '@vuelidate/core'
import { required, numeric } from '@vuelidate/validators'
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'   // optional
import { toast } from '@/lib/notifications'
import { useNetwork } from '@/lib/request'
import { useGlobalLoader } from 'vue-global-loader'

export default defineComponent({
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
        const filters = ref();
        const products = ref<InvestmentPlan[]>([]);
        const newDialog = ref<boolean>(false)
        const productDialog = ref(false);
        const productForm = reactive<InvestmentPlan>(defaultProduct)
        const editForm = reactive<InvestmentPlan>(defaultProduct)
        const product = ref<InvestmentPlan>(defaultProduct);
        const selectedProducts = ref<InvestmentPlan[]>([]);
        const dt = ref(null);
        const deleteProductDialog = ref(false);
        const deleteProductsDialog = ref(false);
        const planFeature = ref<string>('')
        const newFeatures = ref<string[]>([])

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

        const v$ = useVuelidate(rules, productForm)
        const e$ = useVuelidate(rules, editForm)
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
            loadPlans()
        })

        const openNew = () => {
            product.value = defaultProduct
            newDialog.value = true
        };

        const hideDialog = () => {
            productDialog.value = false;
        };

        
        const addProduct = async () => {
            const validation = await v$.value.$validate()
            if(!validation) {
                toast.info('Input Error', 'Please enter a valid amount and currency')
                return
            } else {
                const data = { 
                    plan_name: productForm.name,
                    investment_term: productForm.investment_term,
                    min_amount: productForm.min_amount,
                    description: productForm.description,
                    percentage: productForm.return_percent,
                    features: JSON.stringify(newFeatures.value)
                }
                displayLoader()
                network.push<string, {}>('office/add_plan', data).then((response) => {
                    toast.success(response.data!)
                    newDialog.value = false
                    newFeatures.value = []
                    Object.assign(productForm, defaultProduct)
                    loadPlans()
                    // productForm = defaultProduct
                }).catch((error) => {
                    toast.error(error.message)
                }).finally(() => {
                    destroyLoader()
                })
            }
        };

        const editProduct = (plan: InvestmentPlan) => {
            product.value = { ...plan };
            const details = newFeatures.value.map((c): InvestmentPlanDetail =>  {
                    return {
                        plan_id: product.value.id,
                        feature: c
                    }
                })
            newFeatures.value = details.map(c => c.feature)
            productDialog.value = true;
            Object.assign(editForm, plan)
        };

        const confirmDeleteProduct = (editProduct: {}) => {
            // product.value = editProduct;
            // deleteProductDialog.value = true;
        };

        const deleteProduct = () => {
            // products.value = products.value.filter((val: { id: any; }) => val.id !== product.value.id);
            // deleteProductDialog.value = false;
            // product.value = {};
            // toast.add({ severity: 'success', summary: 'Successful', detail: 'Product Deleted', life: 3000 });
        };

        const findIndexById = (id: any) => {
            // let index = -1;
            // for (let i = 0; i < products.value.length; i++) {
            //     if (products.value[i].id === id) {
            //         index = i;
            //         break;
            //     }
            // }
            // return index;
        };

        const createId = () => {
            // let id = '';
            // const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            // for (let i = 0; i < 5; i++) {
            //     id += chars.charAt(Math.floor(Math.random() * chars.length));
            // }
            // return id;
        };

        const loadPlans = async () => {
            displayLoader()
            network.fetch<InvestmentPlan[], {}>('office/plans', {}).then((response) => {
                // const old = products.value
                products.value = response.data!
                // let merged = [...old, ...response.data!.filter(c => !old.includes(c))]
                // products.value = merged
                // console.log(products.value)
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
            deleteProductsDialog.value = true;
        };
        const deleteSelectedProducts = () => {
            const selected = {
                ids: selectedProducts.value.map(c => c.id)
            }
            displayLoader()
            network.push<string, {}>('office/toggle_plans', selected).then((response) => {
                toast.success(response.data!)
                loadPlans()
            }).catch((error) => {
                console.log(error.message!)
            }).finally(() => {
                destroyLoader()
                deleteProductsDialog.value = false;
            })
            // products.value = products.value.filter((val: any) => !selectedProducts.value.includes(val));
            // deleteProductsDialog.value = false;
            // selectedProducts.value = null;
            // toast.add({ severity: 'success', summary: 'Successful', detail: 'Products Deleted', life: 3000 });
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

        const addFeature = () => {
            const feature = planFeature.value

            if(!newFeatures.value.includes(feature) && feature != '') {
                newFeatures.value.push(feature)
                toast.success('Feature added')
            }
            planFeature.value = ''
        }

        const removeFeature = (index: number) => {
            newFeatures.value.splice(index, 1)
            toast.success('Feature removed')
        }
        
        const saveProduct = async () => {
            const validation = await e$.value.$validate()
            if(!validation) {
                toast.info('Input Error', 'Please enter valid details')
                return
            } else {
               
                const details = newFeatures.value.map((c): InvestmentPlanDetail =>  {
                    return {
                        plan_id: product.value.id,
                        feature: c
                    }
                })
                const data = {
                    id: product.value.id,
                    description: editForm.description ?? '',
                    name: editForm.name,
                    investment_term: editForm.investment_term,
                    min_amount: editForm.min_amount,
                    return_percent: editForm.return_percent,
                    features: JSON.stringify(details),
                    active: editForm.active
                }
                // features: JSON.stringify(newFeatures.value),
                displayLoader()
                network.push<string, InvestmentPlan>('office/edit_plan/'+product.value.id, data).then((response) => {
                    toast.success(response.data!)
                    productDialog.value = false
                    Object.assign(productForm, defaultProduct)
                    loadPlans()
                    // productForm = defaultProduct
                }).catch((error) => {
                    console.log(JSON.stringify(error));
                    toast.error(error.message)
                }).finally(() => {
                    destroyLoader()
                })
            }
        }

        const toggleProduct = (id: string, status: string) => {
            displayLoader()
            network.push<string, {}>('office/toggle_plan', {id: id, status: status}).then((response) => {
                toast.success(response.data!)
                loadPlans()
            })
            .catch(error => toast.info('Error', error.message))
            .finally(() => { destroyLoader() })
        }

        watch(productDialog, (newVal) => {
            console.log(newVal)
            if(newVal == false) {
                product.value = defaultProduct
                newFeatures.value = []
            }
        })

        return { v$, e$, filters, deleteProductsDialog, editForm, toggleProduct, isLoading, removeFeature, newFeatures, addFeature, planFeature, product, productForm, newDialog, products, dt, getBadgeSeverity, selectedProducts,  editProduct, addProduct, confirmDeleteProduct, deleteProduct, createId, productDialog, saveProduct, openNew, hideDialog, confirmDeleteSelected, deleteSelectedProducts, exportCSV }
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
