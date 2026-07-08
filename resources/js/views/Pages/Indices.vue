<template>
    <page-header title="Stock Indices" page="Markets" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">A New Way to Trade US and Global Stock Indices</h3>
                    <span class="text-gray-600 text-2xl">with limited risk and low cost</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        Feel limited by “buy and hold” investing? Frustrated by seeing your portfolio go up one month and down the next? Looking for a way to actively trade the markets, not just invest long-term? 
                    </p>                
                </div>
            </div>
        </div>
    </div>
    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-center">

        <div class="text-center">
            <h2 class="text-900 font-normal mb-2">Stock Index Futures: A World of Opportunity</h2>
            <span class="text-600 text-2xl">on {{ appName }} you can trade binary option and spread contracts based on the underlying futures prices of the following stock indices from the U.S. and the rest of the world.</span>
        </div>

        <div class="grid mt-5">
            <div class="col-12 md:col-4 mb-4 px-0">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-desktop text-4xl text-blue-500"></i> -->
                    <img :src="wallstreet" />
                </span>
                <div class="text-900 mb-3 font-medium">Wall Street 30</div>
                <span class="text-700 text-sm line-height-3">
                  Based on CME E-mini Nasdaq 100® Index Futures, tracking 100 leading non-financial U.S companies.
                </span>
            </div>
            <div class="col-12 md:col-4 mb-4 px-5">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-lock text-4xl text-blue-500"></i> -->
                    <img :src="usTech" />
                </span>
                <div class="text-900 mb-3 font-medium">U.S Tech 100</div>
                <span class="text-700 text-sm line-height-3">
                    Based on CME E-mini Nasdaq 100® Index Futures, tracking 100 leading non-financial U.S. companies.
                </span>
            </div>
            <div class="col-12 md:col-4 mb-4 px-5">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-check-circle text-4xl text-blue-500"></i> -->
                    <img :src="usFlag" />
                </span>
                <div class="text-900 mb-3 font-medium">U.S SmallCap 200</div>
                <span class="text-700 text-sm line-height-3">
                    Based on CME E-mini Russell 2000® Index Futures, tracking 2000 small-cap U.S. equities.
                </span>
            </div>
            <div class="col-12 md:col-4 mb-4 px-5">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-globe text-4xl text-blue-500"></i> -->
                    <img :src="germany" />
                </span>
                <div class="text-900 mb-3 font-medium">Germany 30</div>
                <span class="text-700 text-sm line-height-3">
                    Based on Eurex DAX® Index Futures, 30 major German companies on the Frankfurt Stock Exchange.
                </span>
            </div>
            <div class="col-12 md:col-4 mb-4 px-5">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-github text-4xl text-blue-500"></i> -->
                    <img :src="japan" />
                </span>
                <div class="text-900 mb-3 font-medium">Japan 225</div>
                <span class="text-700 text-sm line-height-3">
                    Based on SGX Nikkei 225® Index Futures, tracking the activities and sentiment of the Japanese stock market.                </span>
            </div>
            <div class="col-12 md:col-4 md:mb-4 mb-0 px-3">
                <span class="p-3 shadow-2 mb-3 inline-block surface-card" style="border-radius: 10px">
                    <!-- <i class="pi pi-shield text-4xl text-blue-500"></i> -->
                    <img :src="china" />
                </span>
                <div class="text-900 mb-3 font-medium">Trusted Securitty</div>
                <span class="text-700 text-sm line-height-3">Mattis rhoncus urna neque viverra justo nec ultrices. Id cursus metus aliquam eleifend.</span>
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

        const wallstreet = base + 'img/wallstreet.jpeg'
        const usTech = base + 'img/ustech.jpeg'
        const usFlag = base + 'img/usaflag.jpeg'
        const germany = base + 'img/germany.jpeg'
        const japan = base + 'img/japanflag.jpeg'
        const china = base + 'img/chflag.jpeg'

        return { wallstreet, usTech, usFlag, germany, appName, japan, china }
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