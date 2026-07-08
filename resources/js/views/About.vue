<template>
    <page-header title="About Us" page="Company" class="w-full lg:w-full md:w-full"></page-header>
    
    
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-">

        <div class="grid mx-6 pt-6 md:pt-8 lg:pt-8">
            <div class="col-12 md:col-8 lg:col-8 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">Who We Are</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ appName }} is one of the leading platforms in Switzerland offering Crude Oil Trading, Forex and spreads. Regulated by the CFTC and based in New York, with member funds held in segregated accounts in major US banks, {{ appName }} offers secure and innovative ways to participate in the markets. 
                </span>
                <p class="text-700 line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    Using {{ appName }} online, traders can take positions on short-term price movements in a wide range of markets. From one account, you can trade stock indices, forex, commodities, bitcoin, and economic events. With 5000 hourly, daily and weekly contracts traded 5 days a week, 24 hours a day, {{ appName }} traders get an ongoing series of short-term trading opportunities. 
                </p>

                <p class="text-700 line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    We help our clients trade financial markets on leverage through CFDs. This product allows you to find opportunity in both rising and falling markets, and isn't confined to one asset class – you can choose to trade on indices, forex, shares, commodities and more:
                </p>

                <p class="text-700 line-height-3 mr-0 md:mr-2 text-justify" style="max-width: 650px">
                    {{ appName }} is also regulated by the International Financial Services Commission of Belize, as well as the Cyprus Securities and Exchange Commission, licensed by the Financial Services Board (FSB) of Switzerland. The company is also registered with the Financial Conduct Authority of the UK.
                </p>

            </div>

            <div class="flex justify-content-start sm:flex-order-2 col-12 md:col-4 lg:col-4 p-0" style="border-radius: 8px">
                <Panel header="Quick Facts">
                    <p class="m-0">
                        <i class="pi pi-chevron-right"></i> US-based, CFTC-regulated exchange 
                    </p>

                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> Member funds held in segregated US bank accounts 
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> Trade multiple global markets from one account 
                    </p>
                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> Limited risk with predefined maximum possible loss 
                    </p>

                    <p class="mt-3">
                        <i class="pi pi-chevron-right"></i> Low minimum opening account balance
                    </p>
                </Panel>
            </div>
        </div>
    </div>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <!-- <div class="text-blue-600 font-bold mb-3"><i class="pi pi-discord"></i>&nbsp;POWERED BY DISCORD</div> -->
            <div class="text-900 font-bold text-5xl mb-3">Join Our Awesome Community</div>
            <div class="text-700 text-2xl mb-5">Ready to be part of the market moves with a global leader in online currency trading?</div>
            <router-link :to="{name: 'Register'}">
                <Button :label="$t('common.joinNow')" class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"></Button>
            </router-link>
        </div>
    </div>
    



</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import Button from 'primevue/button'
import { PageHeader } from '../components';

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
<!-- 
<style scoped>
#hero {
    background: url('img/banner.png');
    /* linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #eeefaf 0%, #c3e3fa 100%); */
    /* height: 200px; */
    overflow: hidden;
}

@media screen and (min-width: 768px) {
    #hero {
        -webkit-clip-path: ellipse(150% 87% at 93% 13%);
        clip-path: ellipse(150% 87% at 93% 13%);
        /* height: 530px; */
    }
}

@media screen and (min-width: 1300px) {
    #hero > img {
        position: absolute;
    }

    #hero > div > p {
        max-width: 450px;
    }
}

@media screen and (max-width: 1300px) {
    #hero {
        height: 600px;
    }

    #hero > img {
        position: static;
        transform: scale(1);
        margin-left: auto;
    }

    #hero > div {
        width: 100%;
    }

    #hero > div > p {
        width: 100%;
        max-width: 100%;
    }
}
</style> -->