<template>
    <page-header :title="$t('app.terms.title')" page="Company" class="w-full lg:w-full md:w-full"></page-header>
    
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-">
        <div class="grid mx-6 pt-6 md:pt-8 lg:pt-8">
            <div class="col-12 md:col-8 lg:col-8 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.riskNotice') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.riskNoticeText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.severability') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.severabilityText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.customerErrors') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.customerErrorsText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.bindingAgreement') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.bindingAgreementText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.expiredOrders') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.expiredOrdersText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.choiceOfLaw') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.choiceOfLawText') }}
                </span>

                <h6 class="line-height-1 text-900 text-4xl font-normal">{{ $t('app.terms.security') }}</h6>
                <span class="line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ $t('app.terms.securityText') }}
                </span>
            </div>

            <div class="flex justify-content-start sm:flex-order-2 col-12 md:col-4 lg:col-4 p-0" style="border-radius: 8px">
                <Panel :header="$t('app.terms.quickFacts')">
                    <p class="m-0">
                        <i class="pi pi-chevron-right"></i> {{ $t('app.terms.fact1') }}
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> {{ $t('app.terms.fact2') }}
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> {{ $t('app.terms.fact3') }}
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> {{ $t('app.terms.fact4') }}
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> {{ $t('app.terms.fact5') }}
                    </p>
                </Panel>
            </div>
        </div>
    </div>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-center">
            <div class="text-900 font-bold text-5xl mb-3">{{ $t('app.terms.joinCommunity') }}</div>
            <div class="text-2xl mb-5">{{ $t('app.terms.joinCommunityDesc') }}</div>
            <router-link :to="{name: 'Register'}">
                <Button :label="$t('common.joinNow')" class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
            </router-link>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import { PageHeader } from '../../components';

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const appName = import.meta.env.VITE_APP_NAME
        const banner = base + 'img/banner.png'
        const margins = base + 'img/marijuana1.png'
        const leverage = base + 'img/choose4.svg'
        const liquidity = base + 'img/choose3.svg'

        return { banner, margins, leverage, liquidity, appName }
    },
    components: {
        Button, PageHeader
    }
})
</script>