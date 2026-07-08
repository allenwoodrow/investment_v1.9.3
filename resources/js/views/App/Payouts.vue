<template>
    <div class="grid">
        <div class="col-12 xl:col-6 lg:col-6">
            <div class="card">
                <h5>{{ $t('app.payouts.tradeResults') }}</h5>
                <DataTable :value="[]" :rows="5" :paginator="true" responsiveLayout="scroll">
                    <Column style="width: 15%">
                        <template #header>{{ $t('common.image') }}</template>
                        <template #body="slotProps">
                            <img :src="'demo/images/product/' + slotProps.data.image" :alt="slotProps.data.image" width="50" class="shadow-2" />
                        </template>
                    </Column>
                    <Column field="name" :header="$t('common.name')" :sortable="true" style="width: 35%"></Column>
                    <Column field="price" :header="$t('common.price')" :sortable="true" style="width: 35%">
                        <template #body="slotProps">
                            {{ formatCurrency(slotProps.data.price_amount) }}
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
        </div>

        <div class="col-12 xl:col-6 lg:col-6">
            <div class="card">
                <Chart type="line" :data="[]" :options="{}" class="h-30rem" />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { formatCurrency } from '../../lib';
import Chart from 'primevue/chart';

export default defineComponent({
    setup() {
        return {}
    },
    components: {
        DataTable, Column, ColumnGroup, Row, Chart
    },
    methods: {
        formatCurrency
    }
})
</script>