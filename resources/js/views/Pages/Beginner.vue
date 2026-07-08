<template>
    <page-header title="Beginner" page="Education" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">What is Forex?</h3>
                    <span class="text-gray-600 text-2xl">A quick overview</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        The foreign exchange market – also known as forex or the FX market – is the world’s most traded market, with turnover of $5 trillion per day. To put this into perspective, the U.S. stock market trades around $257 billion a day; quite a large sum, but only a fraction of what forex trades. Forex is traded 24 hours a day, 5 days a week across by banks, institutions and individual traders worldwide. Unlike other financial markets, there is no centralized marketplace for forex, currencies trade over the counter in whatever market is open at that time.
                    </p>                
                </div>
            </div>
        </div>
    </div>

    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-center">
        <div class="grid mx-6 pt-4 md:pt-7">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">How Forex Trading Works</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    Trading forex involves the buying of one currency and simultaneous selling of another. 
In forex, traders attempt to profit by buying and selling currencies by actively speculating on the direction currencies are likely to take in the future.
                </span>
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="banner" class="w-11" alt="mockup" />
            </div>
        </div>

        <div class="grid mx-6 pt-5 md:pt-7 lg:pt-7">
            <div class="flex justify-content-end flex-order-2 col-12 md:col-6 lg:col-6 p-0 rounded" style="border-radius: 8px; height: 32em;">
                <img :src="liquidity" class="w-11" alt="mockup" />
            </div>
            <div class="col-12 sm:flex-order-1 md:flex-order-2 lg:flex-order-2 md:col-6 lg:col-6 my-auto flex flex-column md:text-right lg:text-right lg:align-items-end md:align-items-end">
                
                <h2 class="line-height-1 text-900 text-4xl font-normal">When can I trade forex?</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    You can trade forex 24 hours a day, five days a week. The foreign exchange markets are worldwide and therefore follow a 24-hour global timetable. 

The trading week for forex begins on Monday morning in Sydney, Australia and follows the sun westward as the world’s major capital markets open and close from Tokyo to London and finally closing on Friday evening in New York.
                </span>
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

        const banner = base + 'img/choose2.svg'
        const liquidity = base + 'img/choose6.svg'
        return { banner, liquidity, appName }
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