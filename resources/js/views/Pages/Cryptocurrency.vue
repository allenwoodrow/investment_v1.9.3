<template>
    <page-header title="Bitcoin" page="Markets" class="w-full lg:w-full md:w-full"></page-header>
    
    <div id="features" class="py-4 px-4 lg:px-8 mt-5 mx-0 lg:mx-8">
        <div class="grid justify-content-center">

            <div class="col-12 mt-8 mb-8 p-2 md:p-8" style="border-radius: 20px; background: linear-gradient(0deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.6)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #efe1af 0%, #c3dcfa 100%)">
                <div class="flex flex-column justify-content-center align-items-center text-center px-3 py-3 md:py-0">
                    <h3 class="text-gray-900 mb-2">Bitcoin Spreads</h3>
                    <span class="text-gray-600 text-2xl">A Better Way</span>
                    <p class="text-gray-900 sm:line-height-2 md:line-height-4 text-2xl mt-4" style="max-width: 800px">
                        Our Bitcoin Spreads let you trade the price of Bitcoin (based on the trusted TeraBit IndexSM) without having to own bitcoins. There's no need for wallets or conversion, since the contracts are settled in US dollars.
Best of all, it's as easy to trade price drops as it is to trade rallies. Short-selling Bitcoin is as easy as buying when you use Bitcoin Spreads.
As the price of Bitcoin varies up and down, the spread's value moves as well, but with limits. Above the ceiling or below the floor, the value of the spread stops moving and remains at its upper or lower limit (depending on whether you are a buyer or seller).
In this way, your risk-reward remains within a defined range. One limit is your profit target. The other is your guaranteed protection against unlimited losses.
                    </p>                
                </div>
            </div>
        </div>
    </div>

    <div class="surface-section px-1 py-4 md:px-6 lg:px-6 text-center">

        <!-- <div class="text-center">
            <h2 class="text-900 font-normal mb-2">Why you should trade on {{ appName }}</h2>
            <span class="text-600 text-2xl">Get in on the action of the most traded market in the world.</span>
        </div> -->

        <div class="grid mx-6 pt-4 md:pt-7">
            <div class="col-12 md:col-6 lg:col-6 my-auto flex flex-column text-center md:text-left lg:text-left lg:align-items-start md:align-items-start">
                <h2 class="line-height-1 text-900 text-4xl font-normal">Bitcoin opportunity without the hassle</h2>
                <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
                    Since its "hacker" beginnings, Bitcoin and cryptocurrency have gone mainstream and soared in value. More traders than ever want to add cryptocurrencies to their portfolios.
However, the volatility makes the Bitcoin market good for short-term trades, not just "buy and hold." 
 </span>
 <span class="text-700 line-height-3 mr-0 md:mr-2" style="max-width: 650px">
 Our Bitcoin Spreads allow you to take short-term positions on the price of Bitcoin, with risk-reward protections built in. Selling is as easy as buying, meaning you have profit opportunities no matter which direction the Bitcoin market is trending. 
Trade the price of Bitcoin without buying and selling the bitcoins themselves. No "mining," no risk exposure outside your comfort level—you can just trade with all the benefits of our platform: limited risk, transparent price, and CFTC regulation.
</span>       
            </div>

            <div class="flex justify-content-end flex-order-1 sm:flex-order-2 col-12 md:col-6 lg:col-6 p-0" style="border-radius: 8px">
                <img :src="margins" class="w-11" alt="mockup" />
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

        const banner = base + 'img/choose1.svg'
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