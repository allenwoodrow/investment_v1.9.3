<template>
    <page-header title="Forex" page="Markets" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">Trade the most popular currency pairs</h3>
                    <span class="text-gray-600 text-2xl">with limited risk and low cost</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        Forex market (short for “foreign exchange”) is the largest and the most liquid financial market where the global currencies are traded. Forex traders purchase currencies with the intent to make money off of the difference between the buying and the selling prices.
                    </p>                
                </div>
            </div>
        </div>
    </div>
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-center">

        <div class="text-center">
            <h2 class="text-900 font-normal mb-2">Why you should trade on {{ appName }}</h2>
            <span class="text-600 text-2xl">Get in on the action of the most traded market in the world.</span>
        </div>

        <div class="grid mx-6 pt-4 md:pt-7">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">High Volume</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    The largest volume market in the world is currency exchange, with an average daily turnover of five trillion dollars. Traded across the global banking system, the spot forex market offers tremendous liquidity and opportunity.
                </span>
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="margins" class="w-11" alt="mockup" />
            </div>
        </div>

        <div class="grid mx-6 pt-5 md:pt-7 lg:pt-7">
            <div class="flex justify-content-end flex-order-2 col-12 md:col-6 lg:col-6 p-0 rounded" style="border-radius: 8px; height: 32em;">
                <img :src="liquidity" class="w-11" alt="mockup" />
            </div>
            <div class="col-12 sm:flex-order-1 md:flex-order-2 lg:flex-order-2 md:col-6 lg:col-6 my-auto flex flex-column md:text-right lg:text-right lg:align-items-end md:align-items-end">
                
                <h2 class="line-height-1 text-900 text-4xl font-normal">Low Spread</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    {{ appName }} offers 10 of the most popular forex pairs as limited-risk binary options and spreads. You can trade them 24 hours a day, 5 days a week.
                    The forex market has an average daily turnover of $5 trillion as traders strive to turn a profit by speculating on the value of one currency compared to another. 
                </span>
            </div>
        </div>

        <div class="grid mx-6 pt-4 md:pt-7">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">Fast execution</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    X-Connected low latency servers within the NY4 datacentre execute your trades with fast execution speeds from as low as 10ms.
                </span>
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="leverage" class="w-full" alt="mockup" />
                <!-- <div class="flex align-items-center justify-content-center bg-yellow-200 align-self-center md:align-left-end lg:align-self-end">
                    <i class="pi pi-fw pi-chart-bar text-4xl text-green-600"></i>
                </div> -->
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
import { PageHeader } from '../../components';

export default defineComponent({
    setup() {
        const base = import.meta.env.VITE_APP_URL
        const appName = import.meta.env.VITE_APP_NAME

        const banner = base + 'img/banner.png'
        const margins = base + 'img/choose2.svg'
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